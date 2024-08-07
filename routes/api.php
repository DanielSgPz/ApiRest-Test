<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('basic.auth')->group(function () {
    Route::post('users/register', [UserController::class, 'registerUser']);
    Route::get('/users', [UserController::class, 'users']);
    Route::get('/users/{id}', [UserController::class, 'findUser']);
    Route::put('/users/{id}', [UserController::class, 'updateUsers']);
    Route::delete('/users/{id}', [UserController::class, 'deleteUser']);
});
