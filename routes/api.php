<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
//Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');


Route::get('/products', [ProductController::class, 'index']);
