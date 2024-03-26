<?php

use App\Http\Controllers\Admin\Courses\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
  'middleware' => 'api',
  'prefix' => 'auth'
], function ($router) {
  Route::post('/login', [AuthController::class, 'login'])->name('login');
  Route::post('/login-tienda', [AuthController::class, 'login_tienda'])->name('login-tienda');
  Route::post('/register', [AuthController::class, 'register'])->name('register');
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');
  Route::get('/user-profile', [AuthController::class, 'user_profile'])->name('user-profile');    
});

Route::group([
  'middleware' => 'api',
], function ($router) {
  Route::resource('/users', UserController::class);
  Route::post('/users/{id}', [UserController::class, 'update']);
});