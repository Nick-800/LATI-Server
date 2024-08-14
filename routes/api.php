<?php

use App\Http\Controllers\ServerController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::post('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'user']);
    Route::post('refresh', [UserController::class, 'refresh']);

    Route::post('servers', [ServerController::class, 'store']);

    Route::get('servers', [ServerController::class,'index']);
    Route::get('servers/{id}', [ServerController::class,'show']);
    Route::put('servers/{id}', [ServerController::class, 'update']);
    Route::delete('servers/{id}', [ServerController::class, 'destroy']);

<<<<<<< HEAD
=======
    Route::post('tasks', [TaskController::class, 'store']);

>>>>>>> 99cac3cd768079754e1d891802d91aa31abdac7e
});
