<?php

use App\Http\Controllers\Admin\Course\CategoryController;
use App\Http\Controllers\Admin\Course\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
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

  Route::resource('/categories', CategoryController::class);
  Route::post('/categories/{id}', [CategoryController::class, 'update']);

  Route::get('/courses/config', [CourseController::class, 'config']);
  Route::resource('/courses', CourseController::class);
  Route::post('/courses/{id}', [CourseController::class, 'update']);
});