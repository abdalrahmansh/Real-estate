<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarPostsController extends Controller
{
    public function cars_post()
    {
        $data = DB::table('users')
        ->join('post_user', 'users.id',  'post_user.user_id')
        ->join('posts', 'post_user.post_id', 'posts.id')
        ->join('cars', 'cars.id', 'posts.postsable_id')
        ->where('posts.postsable_type', 'App\Models\Car')
        ->join('operations', 'post_user.operation_id', 'operations.id')
        ->select('users.name AS TheOwner','cars.description','cars.color','cars.model','cars.name','cars.year','cars.is_new')
        ->where('operations.name', 'like', '%buy%')
        ->get();

        return response()->json($data);
    }

    public function add_car(Request $request)
    {
        // $user = Auth::user();
        $user = User::create([
            'name' => 'John',
            'email' => 'John123123@test.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
        ]);

        $car = new Car();
        $car->name = $request->input('name');
        $car->color = $request->input('color');
        $car->year = $request->input('year');
        $car->is_new = $request->input('is_new');
        $car->description = $request->input('description');
        $price = $request->input('price');
        $car->save();

        $post = new Post(['post_date' => now()]);

        $car->posts()->save($post);

        $post->users()->attach($user, [
            'operation_id' => 2,
            'price' =>  $price
        ]);

        $data = DB::table('users')
            ->join('post_user', 'users.id',  'post_user.user_id')
            ->join('posts', 'post_user.post_id', 'posts.id')
            ->join('cars', 'cars.id', 'posts.postsable_id')
            ->where('posts.postsable_type', 'App\Models\Car')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('users.name AS TheOwner','cars.description','cars.color','cars.model','cars.name','cars.year','cars.is_new')
            ->where('operations.name', 'like', '%buy%')
            ->get();

        return response()->json($data);
    }
}
