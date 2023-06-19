<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes for managing users
Route::group(['prefix' => 'users'], function () {
    // Get all users
    Route::get('/', [UserController::class,'index']);

    // Get a specific user
    Route::get('/{user}', [UserController::class,'show']);

    // Create a new user
    Route::post('/add', [UserController::class,'store']);

    // Update an existing user
    Route::post('edit/{user}', [UserController::class,'update']);

    // Delete a user
    Route::post('delete/{user}', [UserController::class,'destroy']);
});

// Routes for managing posts
Route::group(['prefix' => 'posts'], function () {
    // Get all posts
    Route::get('/', [PostController::class,'index']);

    // Get a specific post
    Route::get('/{post}', [PostController::class,'show']);

    // Create a new post
    Route::post('/add', [PostController::class,'store']);

    // Update an existing post
    Route::post('edit/{post}', [PostController::class,'update']);

    // Delete a post
    Route::post('delete/{post}', [PostController::class,'destroy']);
});

// Route::apiResource('houses', 'App\Http\Controllers\HouseController');
// Route::apiResource('cars', 'App\Http\Controllers\CarController');
// Route::apiResource('lands', 'App\Http\Controllers\LandController');
// Route::apiResource('operations', 'App\Http\Controllers\OperationController');
// Route::apiResource('posts', 'App\Http\Controllers\PostController');
// Route::apiResource('users', 'App\Http\Controllers\UserController');

Route::post('register', [AuthController::class , 'register']);
Route::post('login', [AuthController::class , 'login'])->name('login');
Route::middleware(['api'])->post('logout', [AuthController::class , 'logout']);

Route::get('/lands/{land}', [TestController::class , 'a']);
Route::get('/my-posts', [TestController::class , 'show_my_info']);
Route::get('/post', [TestController::class , 'show_user_info']);
Route::get('/test', [TestController::class , 'show_all_info']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('/cars_post', [TestController::class , 'cars_post']);
    Route::get('/lands_post', [TestController::class , 'lands_post']);
    Route::get('/houses_post', [TestController::class , 'houses_post']);
    
});