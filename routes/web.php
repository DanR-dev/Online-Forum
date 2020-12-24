<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\basicFormController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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
Route::get('/posts', [PostController::class, 'getPosts']);
Route::post('/posts', [PostController::class, 'getPosts']);

Route::post('/posts/edit', [PostController::class, 'editPost']);
Route::post('/posts/delete', [PostController::class, 'deletePost']);
Route::post('/posts/create', [PostController::class, 'createPost']);

Route::post('/comments/edit', [CommentController::class, 'editComment']);
Route::post('/comments/delete', [CommentController::class, 'deleteComment']);
Route::post('/comments/create', [CommentController::class, 'createComment']);

Route::get('/createForm', [basicFormController::class, 'createForm']);
Route::post('/createForm', [basicFormController::class, 'processCreateForm']);

Route::get('/editForm', [basicFormController::class, 'editForm']);
Route::put('/editForm', [basicFormController::class, 'processEditForm']);

Route::get('/login', [loginController::class, 'getLoginForm'])->withoutMiddleware(App\Http\Middleware\Authenticate::class)->name('login');
Route::post('/login', [loginController::class, 'processLogin'])->withoutMiddleware(App\Http\Middleware\Authenticate::class);