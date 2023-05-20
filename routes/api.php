<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('houses', 'App\Http\Controllers\HouseController');
Route::apiResource('cars', 'App\Http\Controllers\CarController');
Route::apiResource('lands', 'App\Http\Controllers\LandController');
Route::apiResource('operations', 'App\Http\Controllers\OperationController');
Route::apiResource('posts', 'App\Http\Controllers\PostController');
Route::apiResource('users', 'App\Http\Controllers\UserController');

Route::post('register', [AuthController::class , 'register']);
Route::post('login', [AuthController::class , 'login'])->name('login');
Route::middleware('auth:api')->post('logout', [AuthController::class , 'logout']);

Route::get('/lands/{land}', [TestController::class , 'a']);
Route::get('/my-posts', [TestController::class , 'show_my_info']);
Route::get('/post', [TestController::class , 'show_user_info']);
Route::get('/test', [TestController::class , 'show_all_info']);
