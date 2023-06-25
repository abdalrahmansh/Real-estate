<?php

namespace App\Http\Controllers;

use App\Models\Land;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandPostsController extends Controller
{
    public function add_car(Request $request)
    {
        // $user = Auth::user();
        $user = User::create([
            'name' => 'John',
            'email' => 'John123123@test.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
        ]);
        
        $land = new Land();
        $land->name = $request->input('name');
        $land->color = $request->input('color');
        $land->year = $request->input('year');
        $land->is_new = $request->input('is_new');
        $land->description = $request->input('description');
        $price = $request->input('price');
        $land->save();

        $post = new Post(['post_date' => now()]);

        $land->posts()->save($post);

        $post->users()->attach($user, [
            'operation_id' => 2,
            'price' =>  $price
        ]);

        $data = DB::table('users')
            ->join('post_user', 'users.id',  'post_user.user_id')
            ->join('posts', 'post_user.post_id', 'posts.id')
            ->join('lands', 'cars.id', 'posts.postsable_id')
            ->where('posts.postsable_type', 'App\Models\Car')
            ->join('operations', 'post_user.operation_id', 'operations.id')
            ->select('users.name AS TheOwner','cars.description','cars.color','cars.model','cars.name','cars.year','cars.is_new')
            ->where('operations.name', 'like', '%buy%')
            ->get();

        return response()->json($data);
    }
}
