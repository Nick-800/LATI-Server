<?php

use App\Http\Controllers\ServerController;
use App\Http\Controllers\SubscriptionController;
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
    Route::get('servers', [ServerController::class, 'index']);
    Route::get('servers/{code}', [ServerController::class, 'show']);
    Route::put('servers/{id}', [ServerController::class, 'update']);
    Route::delete('servers/{id}', [ServerController::class, 'destroy']);

    Route::post('tasks', [TaskController::class, 'store']);
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{id}', [TaskController::class, 'show']);
    Route::put('tasks/{id}', [TaskController::class, 'update']);
    Route::delete('tasks/{id}', [TaskController::class, 'destroy']);

    Route::post('servers/{code}/join', [SubscriptionController::class, 'store']);
    Route::delete('servers/{code}/leave',[SubscriptionController::class,'destroy']);
    Route::get('servers/{code}/users',[SubscriptionController::class,'index']);

});
