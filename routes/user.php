<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\{ ProductController, PageController, UserController };

/*
|--------------------------------------------------------------------------
| Logged-In User Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PageController::class, 'index']);

Route::get('/profile', [UserController::class, 'profile']);
Route::put('/profile', [UserController::class, 'profileUpdate']);
Route::put('/changepass',[UserController::class, 'passwordUpdate']);

Route::resource('products', ProductController::class);
