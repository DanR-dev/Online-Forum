<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\basicFormController;

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

Route::get('/basicForm', [basicFormController::class, 'createForm']);

Route::post('/basicForm', [basicFormController::class, 'process']);