<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\basicFormController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\postsController;
use App\Http\Controllers\commentController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/posts', [postsController::class, 'getPosts']);
Route::post('/posts', [postsController::class, 'getPosts']);

Route::get('/createForm', [basicFormController::class, 'createForm']);
Route::post('/createForm', [basicFormController::class, 'processCreateForm']);

Route::get('/editForm', [basicFormController::class, 'editForm']);
Route::put('/editForm', [basicFormController::class, 'processEditForm']);

Route::get('/login', [loginController::class, 'getLoginForm'])->withoutMiddleware(App\Http\Middleware\Authenticate::class)->name('login');
Route::post('/login', [loginController::class, 'processLogin'])->withoutMiddleware(App\Http\Middleware\Authenticate::class);