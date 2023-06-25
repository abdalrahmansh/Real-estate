<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HouseController;

Route::post('register', [AuthController::class , 'register']);
Route::post('login', [AuthController::class , 'login'])->name('login');
Route::middleware(['auth:api'])->post('logout', [AuthController::class , 'logout']);

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
    Route::get('/', [PostController::class,'index'])->middleware(['auth:api','admin']);

    // Get accepted posts
    Route::get('/accepted/{estate}', [PostController::class,'acceptedPosts']);

    // Get a specific post
    Route::get('/{post}', [PostController::class,'show']);

    // Create a new post
    // Route::post('/add', [PostController::class,'store'])->middleware(['auth:api']);

    // Update an existing post
    // Route::post('edit/{post}', [PostController::class,'update'])->middleware(['auth:api', 'post.ownership']);

    // Delete a post
    Route::post('delete/{post}', [PostController::class,'destroy'])->middleware(['auth:api', 'post.ownership']);

    // Filter houses posts
    Route::post('filter/houses', [HouseController::class,'filter_houses']);

    // Filter cars posts
    // Route::post('filter/cars', [PostController::class,'filter_cars']);

    // Filter lands posts
    // Route::post('filter/lands', [PostController::class,'filter_lands']);
    
    // accepted posts
    Route::post('accept/{post}/{user}', [PostController::class,'accept'])->middleware(['auth:api','admin']);
});

Route::post('houses/add', [HouseController::class,'add_house'])->middleware(['auth:api']);
Route::post('houses/edit/{post}', [HouseController::class,'update_house'])->middleware(['auth:api', 'post.ownership']);

