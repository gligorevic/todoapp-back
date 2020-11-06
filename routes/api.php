<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\TodosController;
use \App\Http\Controllers\Auth\LoginController;
use \App\Http\Controllers\UsersController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource("/todos", TodosController::class)->only(['index', 'show', 'store', 'destroy']);


Route::post('/login', [LoginController::class, "login"]);
Route::post('/logout', [LoginController::class, "logout"])->middleware('customAuth');
Route::post('/refresh', [LoginController::class, "refresh"]); //->middleware('customAuth')


Route::get("/users", [UsersController::class, "show"]);
