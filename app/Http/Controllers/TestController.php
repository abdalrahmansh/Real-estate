<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function houses_filter(Request $request)
    {
        $data = $request->all();
        $results = DB::table('users')
        ->join('post_user', 'users.id',  'post_user.user_id')
        ->join('posts', 'post_user.post_id', 'posts.id')
        ->join('houses', 'houses.id', 'posts.postsable_id')
        ->where('posts.postsable_type', 'App\Models\House')
        ->join('operations', 'post_user.operation_id', 'operations.id')
        ->select('operations.name AS operation_name','users.name','houses.location', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'))
        ->where('users.name', 'like',)
        ->where('operations.name', 'like', '%buy%')
        ->get();

        return response()->json($data);
    }


    public function recommend_post(Request $request)
    {
        $user_name = $request->name;
        $results = DB::table('users')
        ->join('post_user', 'users.id',  'post_user.user_id')
        ->join('posts', 'post_user.post_id', 'posts.id')
        ->join('houses', 'houses.id', 'posts.postsable_id')
        ->where('posts.postsable_type', 'App\Models\House')
        ->join('operations', 'post_user.operation_id', 'operations.id')
        ->select('operations.name AS operation_name','users.name', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'))
        ->where('users.name', 'like', $user_name)
        ->where('operations.name', 'like', '%buy%')
        ->get();

        return response()->json($results);
    }

    public function show_my_info(Request $request)
    {
        $user_name = $request->name;
        $results = DB::table('users')
        ->join('post_user', 'users.id',  'post_user.user_id')
        ->join('posts', 'post_user.post_id', 'posts.id')
        ->join('houses', 'houses.id', 'posts.postsable_id')
        ->where('posts.postsable_type', 'App\Models\House')
        ->join('operations', 'post_user.operation_id', 'operations.id')
        ->select('operations.name AS operation_name','users.name', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'))
        ->where('users.name', 'like', $user_name)
        ->where('operations.name', 'like', '%buy%')
        ->get();

        return response()->json($results);
    }

    public function show_user_info()
    {
        $results = DB::table('users')
        ->join('post_user', 'users.id',  'post_user.user_id')
        ->join('posts', 'post_user.post_id', 'posts.id')
        ->join('houses', 'houses.id', 'posts.postsable_id')
        ->where('posts.postsable_type', 'App\Models\House')
        ->join('operations', 'post_user.operation_id', 'operations.id')
        ->select('operations.name AS operation_name','users.name', DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day'))
        ->where('operations.name', 'like', '%buy%')
        ->get();

        return response()->json($results);
    }

    public function show_all_info()
    {
        $results = DB::table('users')
        ->Join('post_user AS seller', function ($join) {
            $join->on('users.id', '=', 'seller.user_id')->where('seller.operation_id', '=', 1);
        })
        ->Join('post_user AS buyer', function ($join) {
            $join->on('users.id', '=', 'buyer.user_id')->where('buyer.operation_id', '=', 2);
        })
        ->join('posts', 'seller.post_id', '=', 'posts.id')
        ->join('houses', 'houses.id', '=', 'posts.postsable_id')
        ->where('posts.postsable_type', '=', 'App\Models\House')
        ->join('operations', 'seller.operation_id', '=', 'operations.id')
        ->select(
            'operations.name AS operation_name',
            DB::raw('IF(seller.operation_id = 1, users.name, NULL) AS seller_name'),
            DB::raw('IF(buyer.operation_id = 2, users.name, NULL) AS buyer_name'),
            'users.email',
            DB::raw('DATE_FORMAT(posts.post_date, "%d") AS post_day')
        )
        ->where('operations.name', 'like', '%buy%')
        ->get();

    return response()->json($results);

    }
    public function a(House $land)
    {
        $land->load('images.imageable', 'posts.postable', 'posts.users', 'posts.operation');

        return response()->json([
            'id' => $land->id,
            'space' => $land->space,
            'description' => $land->description,
            'images' => $land->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'img' => $image->img,
                    'description' => $image->description,
                    'imageable_id' => $image->imageable_id,
                    'imageable_type' => $image->imageable_type,
                ];
            }),
            'posts' => $land->posts->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'description' => $post->description,
                    'postable_type' => $post->postable_type,
                    'postable_id' => $post->postable_id,
                    'users' => $post->users->map(function ($user) {
                        return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                    }),
                    'operation' => [
                        'id' => $post->operation->id,
                        'name' => $post->operation->name,
                        'notes' => $post->operation->notes,
                    ],
                ];
            }),
        ]);
    }
}
