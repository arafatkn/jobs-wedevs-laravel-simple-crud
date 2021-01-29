<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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
    return 'Working';
});

Route::group(['prefix'=> 'auth', 'as'=> 'auth.'],
    function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/lostpass', [AuthController::class, 'lostpass']);
        Route::delete('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
    }
);
