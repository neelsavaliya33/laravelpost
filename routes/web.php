<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['user']], function () {
    Route::view('/register', 'front.register')->name('user.register');
    Route::post('/register', [UserController::class, 'register'])->name('user.register');

    Route::view('/login', 'front.login')->name('user.login');
    Route::post('/login', [UserController::class, 'login'])->name('user.login');
});



Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('front.home');
    Route::view('/post/create', 'front.createpost')->name('front.post.create');
    Route::post('/post/create', [PostController::class,'create'])->name('front.post.create');
    Route::get('/like/{post}', [PostController::class,'like'])->name('front.post.like');
    Route::post('/comment', [PostController::class,'comment'])->name('front.post.comment');
    Route::get('/comment/{post}', [PostController::class,'allcomment'])->name('front.post.commentall');
    Route::get('/logout', [UserController::class,'logout'])->name('user.logout');
});
