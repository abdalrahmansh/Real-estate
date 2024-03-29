<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\House;
use App\Models\Land;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use App\Models\PostUser;
use App\Models\Operation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $filename = 'avatar.jpg';
        $imagePath = url('storage/' . $filename);

        $operation1 = Operation::create(['name' => 'بيع']); 
        $operation2 = Operation::create(['name' => 'آجار']);
        $operation3 = Operation::create(['name' => 'حجز']);

        // // Create a house with dummy data
        // $house = House::create([
        //     'location' => 'الأشرفية',
        //     'floor' => 3,
        //     'space' => '200',
        //     'room_number' => '4',
        //     'direction' => 'شرقي شمالي',
        //     'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        // ]);

        // Create a user with dummy data
        $user = User::create([
            'name' => 'المدير',
            'email' => 'irshjxxj@gmail.com',
            'phone' => '123456789',
            'img' => $imagePath,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Create an operation with dummy data
        // $operation = Operation::create([
        //     'name' => 'buy',
        //     // Add other operation attributes and their values
        // ]);

        // Create the relation in the PostUser model with dummy data
        // $postUser = PostUser::create([
        //     'user_id' => $user->id,
        //     'operation_id' => $operation1->id,
        //     'postsable_type' => 'App\Models\House', // The class name of the related model
        //     'postsable_id' => $house->id,
        //     'price' => 200,
        // ]);

        // Attach the operation to the post
        // $postUser->operations()->attach($operation);
    

        $user1 = User::create([
            'name' => 'المدير الاحتياطي',
            'email' => 'admin@gmail.com',
            'phone' => '123456789',
            'img' => $imagePath,
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);
        $token = $user1->createToken('authToken',['admin'])->plainTextToken;
        $hashedToken = hash('sha256', $token);
        echo "Token: " . $token . "\n";

        // $user2 = User::create([
        //     'name' => 'David',
        //     'email' => 'David@test.com',
        //     'phone' => '123456789',
        //     'img' => $imagePath,
        //     'password' => bcrypt('password'),
        // ]);
        // $user3 = User::create([
        //     'name' => 'Adam',
        //     'email' => 'Adam@test.com',
        //     'phone' => '123456789',
        //     'img' => $imagePath,
        //     'password' => bcrypt('password'),
        // ]);

        // // HOUSES SECTION 

        // $house1 = House::create([
        //             'location' => 'المارتيني',
        //             'floor' => 'أول',
        //             'space' => '200',
        //             'room_number' => '3',
        //             'direction' => 'غربي',
        //             'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        // ]);
        // $house2 = House::create([
        //             'location' => 'الأشرفية',
        //             'floor' => 'ثالث',
        //             'space' => '200',
        //             'room_number' => '4',
        //             'direction' => 'شرقي شمالي',
        //             'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        // ]);
        // $house3 = House::create([
        //             'location' => 'الأعظمبة',
        //             'floor' => 'رابع',
        //             'space' => '100',
        //             'room_number' => '3',
        //             'direction' => 'شمالي شرقي',
        //             'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        // ]);
        // $house4 = House::create([
        //             'location' => 'سيف الدولة',
        //             'floor' => 'خامس',
        //             'space' => '300',
        //             'room_number' => '3',
        //             'direction' => 'شرقي',
        //             'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        // ]);
        // $house5 = House::create([
        //             'location' => 'السليمانية',
        //             'floor' => 'أول',
        //             'space' => '200',
        //             'room_number' => '3',
        //             'direction' => 'شمالي',
        //             'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        // ]); 


        // // CARS SECTION

        // $car1 = Car::create([
        //     'name' => 'BMW',
        //     'color' => 'Black',
        //     'year' => '2024',
        //     'model' => 'X5',
        //     'is_new' => true,
        //     'description' => 'description',
        // ]);

        // $car2 = Car::create([
        //     'name' => 'BMW',
        //     'color' => 'Green',
        //     'year' => '2024',
        //     'model' => 'X5',
        //     'is_new' => true,
        //     'description' => 'description',
        // ]);

        // $car3 = Car::create([
        //     'name' => 'BMW',
        //     'color' => 'Orange',
        //     'year' => '2024',
        //     'model' => 'X5',
        //     'is_new' => true,
        //     'description' => 'description',
        // ]);


        // // CARS SECTION

        // $land1 = Land::create([
        //     'space' => '6000',
        //     'location' => 'Aleppo',
        //     'description' => 'description',
        // ]);

        // $land2 = Land::create([
        //     'space' => '3000',
        //     'location' => 'Aleppo',
        //     'description' => 'description',
        // ]);

        // $land3 = Land::create([
        //     'space' => '4000',
        //     'location' => 'Aleppo',
        //     'description' => 'description',
        // ]);

        // // IMAGES SECTION 

        // $image1 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-23.jpg']);
        // $image2 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-7.jpg']);
        // $image3 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-19.jpg']);
        // $image4 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);
        // $image5 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);
        // $image6 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);
        // $image7 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);



        // // POSTS SECTION 

        // $post1 = new Post(['post_date' => now()]);
        // $post2 = new Post(['post_date' => now()]);
        // $post3 = new Post(['post_date' => now()]);

        // $post4 = new Post(['post_date' => now()]);
        // $post5 = new Post(['post_date' => now()]);
        // $post6 = new Post(['post_date' => now()]);

        // $post7 = new Post(['post_date' => now()]);
        // $post8 = new Post(['post_date' => now()]);
        // $post9 = new Post(['post_date' => now()]);


        // // POST_USER SECTION

        // //post_user_table

        // // $table->unsignedBigInteger('post_id');
        // // $table->unsignedBigInteger('operation_id');
        // // $table->unsignedBigInteger('user_id');


        // $house1->images()->save($image1);
        // $house2->images()->saveMany([$image2, $image4]);
        // $house3->images()->saveMany([$image5, $image6, $image7]);
        // $house4->images()->save($image3);

        // $house1->posts()->save($post3);
        // $house2->posts()->save($post2);
        // $house3->posts()->save($post1);
        // // $house1->posts()->associate($post3)->save();

        // $car1->posts()->save($post6);
        // $car2->posts()->save($post5);
        // $car3->posts()->save($post4);

        // $land1->posts()->save($post9);
        // $land2->posts()->save($post8);
        // $land3->posts()->save($post7);


        // $post1->users()->attach($user1,[
        //     'operation_id' => $operation1->id,
        //     'price' => 100,
        // ]);
        // $post1->users()->attach($user3,[
        //     'operation_id' => $operation2->id,           
        //     'price' => 100,
        // ]);

        // $post2->users()->attach($user1,[
        //     'operation_id' => $operation1->id,           
        //     'price' => 200,
        // ]);
        // $post2->users()->attach($user2,[
        //     'operation_id' => $operation2->id,           
        //     'price' => 200,
        // ]);

        // $post3->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 300,
        // ]);
        // $post3->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 300,
        // ]);

        // $post4->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 300,
        // ]);
        // $post4->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 300,

        // ]);
        // $post5->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 300,

        // ]);
        // $post5->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 300,

        // ]);
        // $post6->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 3100,

        // ]);
        // $post6->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 3100,

        // ]);

        // $post7->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 3300,

        // ]);
        // $post7->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 3300,

        // ]);

        // $post8->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 3900,

        // ]);

        // $post8->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 3900,

        // ]);
        // $post9->users()->attach($user2,[
        //     'operation_id' => $operation1->id,          
        //     'price' => 4000,

        // ]);
        // $post9->users()->attach($user3,[
        //     'operation_id' => $operation2->id,          
        //     'price' => 4000,

        // ]);

        // $houses = \App\Models\House::factory()
        //     ->count(5)
        //     ->hasImages(3)
        //     // ->hasPosts(3)
        //     ->create();

        // $cars = \App\Models\Car::factory()
        //     ->count(5)
        //     ->hasImages(3)
        //     // ->hasPosts(3)
        //     ->create();

        // $lands = \App\Models\Land::factory()
        //     ->count(5)
        //     ->hasImages(3)
        //     // ->hasPosts(3)
        //     ->create();
            
        // $users = \App\Models\User::factory()
        //     ->count(3)
        //     ->create();

        // $housePosts = $houses->flatMap->postUsers->pluck('id')->toArray();
        // $carPosts = $cars->flatMap->postUsers->pluck('id')->toArray();
        // $landPosts = $lands->flatMap->postUsers->pluck('id')->toArray();
        
        // $usersWithPosts = [
        //     $users[0]->id => $housePosts,
        //     $users[1]->id => $carPosts,
        //     $users[2]->id => $landPosts,
        // ];
        
        // foreach ($usersWithPosts as $userId => $posts) {
        //     foreach ($posts as $postId) {
        //         $operation = rand(0, 1) ? $operation1 : $operation2;
        //         \App\Models\PostUser::create([
        //             'user_id' => $userId,
        //             // 'post_id' => $postId,
        //             'operation_id' => $operation->id,
        //             'price' =>  random_int(1000, 9999),
        //         ]);
        //     }
        // }

    }
}
