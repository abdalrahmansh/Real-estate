<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('password/reset', [AuthController::class , 'resetPasswordView'])->name('password.reset.view');
Route::post('/password/reset', [AuthController::class , 'sendPasswordResetLinkEmail'])->name('new.password');
Route::get('password/forgot', [AuthController::class , 'newPasswordView'])->name('new.password.view');
Route::post('/password/forgot', [AuthController::class , 'resetPassword'])->name('password.reset');
Route::get('/password/success', [AuthController::class , 'newPasswordSuccess'])->name('new.password.success');
Route::get('/password/success/emailed', [AuthController::class , 'resetPasswordSuccess'])->name('reset.password.success');

