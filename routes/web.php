<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('password/reset', [AuthController::class , 'resetPasswordView'])->name('password.reset.view');
Route::post('/password/reset', [AuthController::class , 'sendPasswordResetLinkEmail'])->name('password.reset');
Route::get('password/forgot', [AuthController::class , 'newPasswordView'])->name('new.password.view');
Route::post('/password/forgot', [AuthController::class , 'resetPassword'])->name('new.password');
Route::get('/password/success', [AuthController::class , 'newPasswordSuccess'])->name('new.password.success');

