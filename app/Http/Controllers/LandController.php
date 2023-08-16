<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Land;
use App\Models\Image;
use App\Models\PostUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LandController extends Controller
{
    public function filter_lands(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'string',
            'min_space' => 'integer',
            'max_space' => 'integer',
            'min_price' => 'integer',
            'max_price' => 'integer',
            'operation_id' => 'integer',
        ]);

        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $location = $request->input('location');
        $min_space = $request->input('min_space');
        $max_space = $request->input('max_space');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $operationId = $request->input('operation_id');

        $data = DB::table('users')
            ->join('post_user', 'users.id',  'post_user.user_id')
            ->join('posts', 'post_user.post_id', 'posts.id')
            ->join('lands', 'lands.id', 'posts.postsable_id')
            ->where('posts.postsable_type', 'App\Models\Land')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('users.name AS TheOwner','lands.space','lands.description',)
            ->where('post_user.operation_id', $operationId)
            ->where('lands.location', 'like', '%'.$location.'%')
            ->whereBetween('lands.space', [$min_space, $max_space])
            ->whereBetween('post_user.price', [$min_price, $max_price])
            ->get();

            if(empty($data)){
                return response()->json($data);
            }
            return response()->json(['message' => 'Nothing matched the giving information'], 404);
    }

    public function add_land(Request $request)
    {
        $user = Auth::user();

        $land = new Land();
        $land->space = $request->input('space');
        $land->location = $request->input('location');
        $land->description = $request->input('estateDescription');
        $price = $request->input('price');
        $duration = $request->input('duration');
        $operation_id = $request->input('operation_id');
        $postDescription = $request->input('postDescription');

        $validator = Validator::make($request->all(), [
            'space' => 'required|integer',
            'location' => 'required|string',
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

        $land->save();

        $post = new PostUser([
            'operation_id' => $operation_id,
            'user_id' => $user->id,
            'description' => $postDescription,
            'duration' => $duration,
            'price' => $price,
            'post_date' => now()
        ]);
        $land->images()->saveMany($images);

        $land->postUsers()->save($post);
        
        return redirect()->route('posts.show', ['post' => $post]);
    }

    public function update_land(Request $request, $id)
    {
        $user = Auth::user();

        $post = PostUser::findOrFail($id);
        $land = $post->postsable;
        $land->space = $request->input('space');
        $land->location = $request->input('location');
        $land->description = $request->input('estateDescription');
        $price = $request->input('price');
        $duration = $request->input('duration');
        $operation_id = $request->input('operation_id');
        $postDescription = $request->input('postDescription');

        $validator = Validator::make($request->all(), [
            'space' => 'integer',
            'location' => 'string',
            'price' => 'integer',
            'operation_id' => 'integer',
            'images.*' => 'nullable|image',
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

        $land->save();

        if (!empty($images)) {
            $land->images()->delete();
            $land->images()->saveMany($images);
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
