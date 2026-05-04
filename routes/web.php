<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Routes protégées du back-office.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::view('/api-demo', 'api-demo')->name('api-demo');

    Route::resource('products', ProductController::class)
        ->except('show')
        ->middleware('can:manage-products');

    Route::resource('menus', MenuController::class)
        ->except('show')
        ->middleware('can:manage-menus');

    Route::resource('users', UserController::class)
        ->except('show')
        ->middleware('can:manage-users');

    // Routes des commandes.
    Route::get('orders', [OrderController::class, 'index'])->middleware('can:view-orders')->name('orders.index');
    Route::get('orders/create', [OrderController::class, 'create'])->middleware('can:create-orders')->name('orders.create');
    Route::post('orders', [OrderController::class, 'store'])->middleware('can:create-orders')->name('orders.store');
    Route::get('orders/{order}/edit', [OrderController::class, 'edit'])->middleware('can:manage-order-details')->name('orders.edit');
    Route::put('orders/{order}', [OrderController::class, 'update'])->middleware('can:manage-order-details')->name('orders.update');
    Route::put('orders/{order}/ready', [OrderController::class, 'ready'])->middleware('can:mark-order-ready')->name('orders.ready');
    Route::put('orders/{order}/delivered', [OrderController::class, 'delivered'])->middleware('can:mark-order-delivered')->name('orders.delivered');
});

require __DIR__ . '/auth.php';
