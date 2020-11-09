<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TodosController;
use \App\Http\Controllers\Auth\AuthController;
use \App\Http\Controllers\UsersController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource("/todos", TodosController::class)->only(['index', 'update', 'store', 'destroy']);


Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::post('/logout', [AuthController::class, "logout"]);
Route::post('/refresh', [AuthController::class, "refresh"])->middleware("userHasToken");


Route::get("/users", [UsersController::class, "show"]);
