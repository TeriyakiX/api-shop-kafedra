<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use \App\Http\Controllers\Api\CartUserController;
use \App\Http\Controllers\Api\OrderController;


Route::middleware(['notfound.json'])->group(function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/products', [ProductController::class, 'index']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::middleware(['auth:api', 'user'])->group(function () {
        Route::get('/cart', [CartUserController::class, 'viewCart']);
        Route::post('/cart/add', [CartUserController::class, 'addToCart']);
        Route::delete('/cart/destroy', [CartUserController::class, 'destroyFromCart']);

        Route::get('/orders', [OrderController::class, 'viewOrders']);
        Route::post('/order/add', [OrderController::class, 'createOrder']);
    });

    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::post('/createProducts', [ProductController::class, 'createProducts']);
        Route::put('/updateProducts', [ProductController::class, 'updateProducts']);
        Route::delete('/destroyProducts', [ProductController::class, 'destroyProducts']);
    });

});

