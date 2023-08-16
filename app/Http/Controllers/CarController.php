<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Image;
use App\Models\Post;
use App\Models\PostUser;
use App\Models\House;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function filter_cars(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'min_space' => 'integer',
            'max_space' => 'integer',
            'color' => 'string',
            'is_new' => 'integer',
            'min_year' => 'integer',
            'max_year' => 'integer',
            'min_price' => 'integer',
            'max_price' => 'integer',
            'operation_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $name = $request->input('name');
        $min_year = $request->input('min_year');
        $max_year = $request->input('max_year');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $operationId = $request->input('operation_id');

        $data = DB::table('users')
            ->join('post_user', 'users.id',  'post_user.user_id')
            ->join('posts', 'post_user.post_id', 'posts.id')
            ->join('cars', 'cars.id', 'posts.postsable_id')
            ->where('posts.postsable_type', 'App\Models\House')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('users.name AS TheOwner','cars.name','cars.year','cars.name','cars.model','cars.description', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'))
            ->where('post_user.operation_id', $operationId)
            ->where('cars.name', 'like', '%'.$name.'%')
            ->whereBetween('cars.year', [$min_year, $max_year])
            ->whereBetween('post_user.price', [$min_price, $max_price])
            ->get();

            if(empty($data)){
                return response()->json($data);
            }
            return response()->json(['message' => 'Nothing matched the giving information'], 404);
    }
    
    public function add_car(Request $request)
    {
        $user = Auth::user();

        $car = new Car();
        $car->name = $request->input('name');
        $car->model = $request->input('model');
        $car->color = $request->input('color');
        $car->is_new = $request->input('is_new');
        $car->year = $request->input('year');
        $car->description = $request->input('estateDescription');
        $price = $request->input('price');
        $duration = $request->input('duration');
        $operation_id = $request->input('operation_id');
        $postDescription = $request->input('postDescription');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'color' => 'required|string',
            'is_new' => 'required|boolean',
            'model' => 'required|string',
            'year' => 'required|string',
            'price' => 'required|integer',
            'operation_id' => 'required|integer',
            'images.*' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $images = [];
        foreach ($request->file('images', []) as $image) {
            $path = $image->store('public');
            $filename = basename($path);
            $url = asset('storage/' . $filename);
            $imageModel = new Image(['img' => $url]);
            $images[] = $imageModel;
        }

        $car->save();

        $post = new PostUser([
            'operation_id' => $operation_id,
            'user_id' => $user->id,
            'description' => $postDescription,
            'duration' => $duration,
            'price' => $price,
            'post_date' => now()
        ]);
        $car->images()->saveMany($images);

        $car->postUsers()->save($post);
        
        return redirect()->route('posts.show', ['post' => $post]);
    }

    
    public function update_car(Request $request, $id)
    {
        $user = Auth::user();

        $post = PostUser::findOrFail($id);
        $car = $post->postsable;
        $car->name = $request->input('name', $car->name);
        $car->model = $request->input('model', $car->model);
        $car->color = $request->input('color', $car->color);
        $car->is_new = $request->input('is_new', $car->is_new);
        $car->year = $request->input('year', $car->year);
        $car->description = $request->input('estateDescription', $car->description);
        $price = $request->input('price');
        $duration = $request->input('duration');
        $operation_id = $request->input('operation_id');
        $postDescription = $request->input('postDescription');

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'color' => 'required|string',
            'is_new' => 'required|boolean',
            'model' => 'required|string',
            'year' => 'required|string',
            'description' => 'string',
            'price' => 'required|integer',
            'operation_id' => 'required|integer',
            'images.*' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public');
                $filename = basename($path);
                $url = asset('storage/' . $filename);
                $imageModel = new Image(['img' => $url]);
                $images[] = $imageModel;
            }
        }

        $car->save();

        if (!empty($images)) {
            $car->images()->delete();
            $car->images()->saveMany($images);
        }

        $post->update([
            'operation_id' => $operation_id,
            'description' => $postDescription  ?? null,
            'duration' => $duration  ?? null,
            'user_id' => $user->id,
            'price' => $price,
        ]);
        
        return redirect()->route('posts.show', ['post' => $post]);
    }
}
