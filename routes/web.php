<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\basicFormController;
use App\Http\Controllers\loginController;

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

Route::get('/basicShow', function () {
    return view('basicShow', ['posts' => Post::get()]);
});

Route::get('/createForm', [basicFormController::class, 'createForm']);
Route::post('/createForm', [basicFormController::class, 'processCreateForm']);

Route::get('/editForm', [basicFormController::class, 'editForm']);
Route::put('/editForm', [basicFormController::class, 'processEditForm']);

Route::get('/login', function()
{
    return view('loginForm');
});
Route::post('/login', [loginController::class, 'authenticate']);