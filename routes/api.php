<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\LandController;
use App\Http\Controllers\NotificationsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HouseController;
use App\Models\PostUser;

Route::middleware(['cors'])->group(function () {
    
    Route::post('register', [AuthController::class , 'register']);
    Route::post('login', [AuthController::class , 'login'])->name('login');
    Route::post('logout', [AuthController::class , 'logout'])->middleware(['auth:api']);
    Route::post('admin/login', [AuthController::class , 'adminLogin'])->name('admin.login');
	Route::get('/user', [AuthController::class , 'user'])->middleware(['auth:api']);

    // Routes for managing users
    Route::group(['prefix' => 'users'], function () {
        // Get all users
        Route::get('/', [UserController::class,'index'])->middleware(['auth:api','admin']);

        // Get a specific user
        Route::get('/{user}', [UserController::class,'show']);

        // // Create a new user
        // Route::post('/add', [UserController::class,'store'])->middleware(['auth:api']);

        // Update an existing user
        Route::post('edit/{user}', [UserController::class,'update'])->middleware(['auth:api','user.ownership']);

        // Delete a user
        Route::post('delete/{user}', [UserController::class,'destroy'])->middleware(['auth:api','user.ownership']);;
    });

    // Routes for managing posts
    Route::group(['prefix' => 'posts'], function () {
        // Get all posts
        Route::get('/', [PostController::class,'allPosts'])->middleware(['auth:api']);
        
        // Get all posts that need admin review
        Route::get('/review/{estate}', [PostController::class,'postsNeedReview'])->middleware(['auth:api','admin']);

        // Get accepted posts
        Route::get('/accepted/{estate}', [PostController::class,'acceptedPosts']);

        // Get a specific post
        Route::get('/{post}', [PostController::class,'show'])->name('posts.show');

        // Get a specific post
        Route::get('/admin/deleted/posts', [PostController::class,'deletedPosts'])->name('posts.deleted');
        
        // Get a specific post
        Route::get('/admin/{post}', [PostController::class,'showWithoutCounter'])->name('posts.showWithoutCounter');

        // Get a user's posts
        Route::get('/user/posts/{estate}', [PostController::class,'aUserPosts'])->middleware(['auth:api']);

        // reserve an estate
        Route::post('/reserve/{post}', [PostController::class, 'reserve'])->middleware('auth:api');

        // Get all posts that need admin review for reserving
        Route::get('/review/reserve', [PostController::class,'postsNeedReviewToReserving'])->middleware(['auth:api','admin']);

        // Delete a post
        Route::post('delete/{post}', [PostController::class,'destroy'])->middleware(['auth:api']);

        // Filter houses posts
        Route::post('filter/houses', [HouseController::class,'filter_houses']);

        // Filter cars posts
        Route::post('filter/cars', [CarController::class,'filter_cars']);

        // Filter lands posts
        Route::post('filter/lands', [LandController::class,'filter_lands']);
        
        // accept post
        Route::post('accept/{post}', [PostController::class,'accept'])->middleware(['auth:api','admin']);
        
        // reject post
        Route::post('reject/{post}', [PostController::class,'reject'])->middleware(['auth:api','admin']);
    });

    Route::post('houses/add', [HouseController::class,'add_house'])->middleware(['auth:api']);
    Route::post('houses/edit/{post}', [HouseController::class,'update_house'])->middleware(['auth:api', 'post.ownership']);

    Route::post('cars/add', [CarController::class,'add_car'])->middleware(['auth:api']);
    Route::post('cars/edit/{post}', [CarController::class,'update_car'])->middleware(['auth:api', 'post.ownership']);

    Route::post('lands/add', [LandController::class,'add_land'])->middleware(['auth:api']);
    Route::post('lands/edit/{post}', [LandController::class,'update_land'])->middleware(['auth:api']);


    Route::get('/notifications', [NotificationsController::class, 'showUnreadNotifications'])->middleware('auth:api');
    Route::post('/notifications/{notification}', [NotificationsController::class, 'readNotification'])->middleware('auth:api');

});
