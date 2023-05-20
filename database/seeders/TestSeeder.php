<?php

namespace Database\Seeders;

use App\Models\House;
use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use App\Models\Operation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::create([
            'name' => 'John',
            'email' => 'John@test.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
        ]);
        $user2 = User::create([
            'name' => 'David',
            'email' => 'David@test.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
        ]);
        $user3 = User::create([
            'name' => 'Adam',
            'email' => 'Adam@test.com',
            'phone' => '123456789',
            'password' => bcrypt('password'),
        ]);

        $operation1 = Operation::create(['name' => 'Sell','notes' => 'nothing']); 
        $operation2 = Operation::create(['name' => 'Buy','notes' => 'nothing']); 
        $operation3 = Operation::create(['name' => 'Rent','notes' => 'nothing']);

        // HOUSES SECTION 

        $house1 = House::create([
                    'location' => 'المارتيني',
                    'floor' => 'أول',
                    'space' => '200',
                    'no_rooms' => '3',
                    'direction' => 'غربي',
                    'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        ]);
        $house2 = House::create([
                    'location' => 'الأشرفية',
                    'floor' => 'ثالث',
                    'space' => '200',
                    'no_rooms' => '4',
                    'direction' => 'شرقي شمالي',
                    'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        ]);
        $house3 = House::create([
                    'location' => 'الأعظمبة',
                    'floor' => 'رابع',
                    'space' => '100',
                    'no_rooms' => '3',
                    'direction' => 'شمالي شرقي',
                    'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        ]);
        $house4 = House::create([
                    'location' => 'سيف الدولة',
                    'floor' => 'خامس',
                    'space' => '300',
                    'no_rooms' => '3',
                    'direction' => 'شرقي',
                    'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        ]);
        $house5 = House::create([
                    'location' => 'السليمانية',
                    'floor' => 'أول',
                    'space' => '200',
                    'no_rooms' => '3',
                    'direction' => 'شمالي',
                    'description' => 'Near of a pharmacy and a hospital, no need for fixing or any furniture, the house is ready.',
        ]); 

        // IMAGES SECTION 

        $image1 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-23.jpg']);
        $image2 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-7.jpg']);
        $image3 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-19.jpg']);
        $image4 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);
        $image5 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);
        $image6 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);
        $image7 = new Image(['img' => 'https://echinest.sirv.com/Estates/download-14.jpg']);



        // POSTS SECTION 

        $post1 = new Post(['post_date' => now()]);
        $post2 = new Post(['post_date' => now()]);
        $post3 = new Post(['post_date' => now()]);


        // POST_USER SECTION

        //post_user_table

        // $table->unsignedBigInteger('post_id');
        // $table->unsignedBigInteger('operation_id');
        // $table->unsignedBigInteger('user_id');


        $house1->images()->save($image1);
        $house2->images()->saveMany([$image2, $image4]);
        $house3->images()->saveMany([$image5, $image6, $image7]);
        $house4->images()->save($image3);

        $house1->posts()->save($post3);
        $house2->posts()->save($post2);
        $house3->posts()->save($post1);
        // $house1->posts()->associate($post3)->save();

        $post1->users()->attach($user1,['operation_id' => $operation1->id]);
        $post1->users()->attach($user3,['operation_id' => $operation2->id]);

        $post2->users()->attach($user1,['operation_id' => $operation1->id]);
        $post2->users()->attach($user2,['operation_id' => $operation2->id]);

        $post3->users()->attach($user2,['operation_id' => $operation1->id]);
        $post3->users()->attach($user3,['operation_id' => $operation2->id]);

    }
}
