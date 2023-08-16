<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\PostUser;
use Illuminate\Http\Request;
use App\Models\House;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HouseController extends Controller
{

    public function filter_houses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'string',
            'min_space' => 'integer',
            'max_space' => 'integer',
            'direction' => 'string',
            'floor' => 'integer',
            'min_room_number' => 'integer',
            'max_room_number' => 'integer',
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
        $direction = $request->input('direction');
        $floor = $request->input('floor');
        $min_room_number = $request->input('min_room_number');
        $max_room_number = $request->input('max_room_number');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $operationId = $request->input('operation_id');

        $data = DB::table('users')
            ->join('post_user', 'users.id',  'post_user.user_id')
            ->join('houses', 'houses.id', 'post_user.postsable_id')
            ->where('post_user.postsable_type', 'App\Models\House')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('post_user.id')
            ->where('post_user.operation_id', $operationId)
            ->where('houses.location', 'like', '%'.$location.'%')
            ->whereBetween('houses.space', [$min_space, $max_space])
            ->where('houses.direction', 'like', '%'.$direction.'%')
            ->where('houses.floor', $floor)
            ->where('houses.direction', 'like', '%'.$direction.'%')
            ->whereBetween('houses.room_number', [$min_room_number, $max_room_number])
            ->whereBetween('post_user.price', [$min_price, $max_price])
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Nothing matched the given information'], 404);
        }
    
        $postIds = $data->pluck('id'); // Extract IDs from the query result
    
        $posts = PostUser::with('user', 'operation', 'postsable', 'postsable.images')
            ->whereIn('id', $postIds)
            ->where('is_accepted', 1)
            ->get();
        
        return response()->json($posts);
    }
    
    public function add_house(Request $request)
    {
        $user = Auth::user();

        $house = new House();
        $house->location = $request->input('location');
        $house->space = $request->input('space');
        $house->direction = $request->input('direction');
        $house->floor = $request->input('floor');
        $house->room_number = $request->input('room_number');
        $house->description = $request->input('estateDescription');
        $price = $request->input('price');
        $duration = $request->input('duration');
        $operation_id = $request->input('operation_id');
        $postDescription = $request->input('postDescription');

        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'direction' => 'required|string',
            'floor' => 'required|integer',
            'space' => 'required|integer',
            'room_number' => 'required|integer',
            'description' => 'string',
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

        $house->save();

        // $post = new Post(['post_date' => now()]);
        $post = new PostUser([
            'operation_id' => $operation_id,
            'user_id' => $user->id,
            'description' => $postDescription,
            'duration' => $duration,
            'price' => $price,
        ]);
        $house->images()->saveMany($images);

        $house->postUsers()->save($post);
        
        return redirect()->route('posts.show', ['post' => $post]);
    }

    
    public function update_house(Request $request, $id)
    {
        $user = Auth::user();

        $post = PostUser::findOrFail($id);
        $house = $post->postsable;
        $house->location = $request->input('location', $house->location);
        $house->space = $request->input('space', $house->space);
        $house->direction = $request->input('direction', $house->direction);
        $house->floor = $request->input('floor', $house->floor);
        $house->room_number = $request->input('room_number', $house->room_number);
        $house->description = $request->input('estateDescription', $house->description);
        $price = $request->input('price');
        $duration = $request->input('duration');
        $operation_id = $request->input('operation_id');
        $postDescription = $request->input('postDescription');

        $validator = Validator::make($request->all(), [
            'location' => 'string',
            'direction' => 'string',
            'floor' => 'integer',
            'space' => 'integer',
            'room_number' => 'integer',
            'description' => 'string',
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

        $house->save();

        if (!empty($images)) {
            $house->images()->delete();
            $house->images()->saveMany($images);
        }
    
        $post->update([
            'operation_id' => $operation_id,
            'description' => $postDescription ?? null,
            'duration' => $duration ?? null,
            'user_id' => $user->id,
            'price' => $price,
        ]);
        
        return redirect()->route('posts.show', ['post' => $post]);
    }
}
