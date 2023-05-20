<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Estate;
use App\Models\House;
use App\Models\Car;
use App\Models\Land;
use App\Models\Image;
use App\Models\Operation;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // \App\Models\User::factory()->count(10)->create();

        // $house1 = House::create(['location' => 'salah edin','floor' => 'first']);
        // $house2 = House::create(['location' => 'salah edin','floor' => 'second']);
        // $house3 = House::create(['location' => 'salah edin','floor' => 'third']);

        // $images = [
        //     new Image(['img' => 'https://echinest.sirv.com/Estates/download-23.jpg']),
        //     new Image(['img' => 'https://echinest.sirv.com/Estates/download-7.jpg']),
        //     new Image(['img' => 'https://echinest.sirv.com/Estates/download-19.jpg']),
        //     new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']),
        // ];
        // // $img1 = Image::create(['img' => '123.png']);
        // // $img2 = Image::create(['img' => '123.png']);
        // // $img3 = Image::create(['img' => '123.png']);

        // $house1->images()->saveMany($images);

        // $user1 = User::create([
        //     'name' => 'John',
        //     'email' => 'John@test.com',
        //     'password' => bcrypt('password'),
        // ]);
        // $user2 = User::create([
        //     'name' => 'David',
        //     'email' => 'David@test.com',
        //     'password' => bcrypt('password'),
        // ]);
        // $user3 = User::create([
        //     'name' => 'Adam',
        //     'email' => 'Adam@test.com',
        //     'password' => bcrypt('password'),
        // ]);

        // $post1 = $estate1->posts()->create([
        //     'title' => 'post 1',
        //     'description' => 'Description Post',
        // ]);
        // $post2 = $estate1->posts()->create([
        //     'title' => 'post 2',
        //     'description' => 'Description Post',
        // ]);
        // $post3 = $estate1->posts()->create([
        //     'title' => 'post 3',
        //     'description' => 'Description Post',
        // ]);

        // $post1->users()->attach($user1,['operation_id' => $operation1->id]);
        // $post1->users()->attach($user2,['operation_id' => $operation2->id]);
        // $post2->users()->attach($user3,['operation_id' => $operation1->id]);
        // $post2->users()->attach($user1,['operation_id' => $operation2->id]);


        /*todo
        SELECT users.user_name, posts.title, operations.title
            FROM users
            INNER JOIN post_user_operation ON users.id = post_user_operation.user_id
            INNER JOIN posts ON post_user_operation.post_id = posts.id
            INNER JOIN operations ON post_user_operation.operation_id = operations.id
            WHERE operations.title LIKE '%buy%';

        $results = DB::table('users')
            ->join('post_user_operation', 'users.id', '=', 'post_user_operation.user_id')
            ->join('posts', 'post_user_operation.post_id', '=', 'posts.id')
            ->join('operations', 'post_user_operation.operation_id', '=', 'operations.id')
            ->select('users.user_name', 'posts.title', 'operations.title')
            ->where('operations.title', 'like', '%buy%')
            ->get();

        */

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(TestSeeder::class);
    
    }
}
