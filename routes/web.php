<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

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

Route::redirect('/','/posts');
Route::get('/posts', [PostController::class, 'getPosts']); // get posts
Route::post('/posts', [PostController::class, 'getPosts']); // get posts by specified author

Route::post('/posts/edit', [PostController::class, 'editPost']); // edit specified post
Route::post('/posts/delete', [PostController::class, 'deletePost']); // delete specified post
Route::post('/posts/create', [PostController::class, 'createPost']); // create new post

Route::post('/comments/edit', [CommentController::class, 'editComment']); // edit specified comment
Route::post('/comments/delete', [CommentController::class, 'deleteComment']); // delete specified comment
Route::post('/comments/create', [CommentController::class, 'createComment']); // create new comment

// get login form (if not logged in) or account options (if logged in)
Route::get('/login', [LoginController::class, 'getAccountOptionsView'])->withoutMiddleware(App\Http\Middleware\Authenticate::class)->name('login');
Route::post('/login', [LoginController::class, 'processLoginRequest'])->withoutMiddleware(App\Http\Middleware\Authenticate::class); // attemp login
Route::post('/logout', [LoginController::class, 'processLogoutRequest']); // attempt logout
Route::post('/avatar/set', [ProfileController::class, 'setAvatar']); // change avatar image