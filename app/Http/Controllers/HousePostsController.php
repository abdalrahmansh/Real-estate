<?php

namespace App\Http\Controllers;

use App\Models\House;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HousePostsController extends Controller
{
    public function show_houses()
    {
        $data = DB::table('users')
        ->join('post_user', 'users.id',  'post_user.user_id')
        ->join('posts', 'post_user.post_id', 'posts.id')
        ->join('houses', 'houses.id', 'posts.postsable_id')
        ->where('posts.postsable_type', 'App\Models\House')
        ->join('operations', 'post_user.operation_id', 'operations.id')
        ->select('users.name AS TheOwner','houses.location','houses.space','houses.direction','houses.floor','houses.room_number','houses.description', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'),'post_user.price')
        // ->where('operations.name', 'like', '%buy%')
        ->get();

        return response()->json($data);
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
        $house->description = $request->input('description');
        $price = $request->input('price');
        
        $operation_id = $request->input('operation_id');

        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'direction' => 'required|string',
            'floor' => 'required|integer',
            'space' => 'required|integer',
            'room_number' => 'required|integer',
            'description' => 'string',
            'price' => 'required|integer',
            'operation_id' => 'required|integer',
            'images' => 'required',
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

        $post = new Post(['post_date' => now()]);
        $house->images()->saveMany($images);

        $house->post()->save($post);

        $post->users()->attach($user->id, [
            'operation_id' => $operation_id,
            'price' =>  $price
        ]);
        
        return response()->json(['message' => 'House post added successfully']);
        // return response()->json([
        //     'id' => $house->id,
        //     'location' => $house->location,
        //     'space' => $house->space,
        //     'direction' => $house->direction,
        //     'floor' => $house->floor,
        //     'room_number' => $house->room_number,
        //     'description' => $house->description,
        //     'price' => $price,
        //     'operation' => $post->operations->pluck('name'),
        //     'images' => $house->images->map(function ($image) {
        //         return [
        //             'id' => $image->id,
        //             'img' => $image->img,
        //         ];
        //     }),
        //     'posts' => $house->posts->map(function ($post) {
        //         return [
        //             'id' => $post->id,
        //             'post_date' => $post->post_date,
        //             'users' => $post->users->map(function ($user) {
        //                 return [
        //                     'id' => $user->id,
        //                     'name' => $user->name,
        //                     'email' => $user->email,
        //                     // 'operation' => $user->post_user->operation,

        //                 ];
        //             }),
                    // 'operation' => $post->users->map(function ($operation) {
                    //     return [
                    //         'id' => $operation->id,
                    //         'name' => $operation->name,
                    //         'notes' => $operation->notes,
                    //         'operation' => $user->pivot->operation,

                    //     ];
                    // }),
                    // 'operation' => [
                    //     'id' => $post->operation->id,
                    //     'name' => $post->operation->name,
                    //     'notes' => $post->operation->notes,
                    // ],
        //         ];
        //     }),
        // ]);
    }

    public function filter_houses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'min_space' => 'required|integer',
            'max_space' => 'required|integer',
            'direction' => 'required|string',
            'floor' => 'required|integer',
            'min_room_number' => 'required|integer',
            'max_room_number' => 'required|integer',
            'min_price' => 'required|integer',
            'max_price' => 'required|integer',
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
            ->join('posts', 'post_user.post_id', 'posts.id')
            ->join('houses', 'houses.id', 'posts.postsable_id')
            ->where('posts.postsable_type', 'App\Models\House')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('users.name AS The Owner','houses.location','houses.space','houses.direction','houses.floor','houses.room_number','houses.description', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'))
            ->where('post_user.operation_id', $operationId)
            ->where('houses.location', 'like', '%'.$location.'%')
            ->whereBetween('houses.space', [$min_space, $max_space])
            ->where('houses.direction', 'like', '%'.$direction.'%')
            ->where('houses.floor', 'like', '%'.$floor.'%')
            ->where('houses.direction', 'like', '%'.$direction.'%')
            ->whereBetween('houses.room_number', [$min_room_number, $max_room_number])
            ->whereBetween('post_user.price', [$min_price, $max_price])
            ->get();

            if(empty($data)){
                return response()->json($data);
            }
            return response()->json(['message' => 'Nothing matched the giving information'], 404);
    }

    

}
