<?php

use App\Http\Controllers\Api\MenuApiController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::get('/menus', [MenuApiController::class, 'index']);
Route::get('/products', [ProductApiController::class, 'index']);
Route::post('/orders', [OrderApiController::class, 'store']);
