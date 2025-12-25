<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk');

        Route::resource('products', ProductController::class);
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
        Route::put('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.stock.update');

        // Orders Routes
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
        Route::get('orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::patch('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('orders/{id}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
        Route::post('orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

        // Order Items Management
        Route::post('orders/{id}/items', [OrderController::class, 'addItem'])->name('orders.items.add');
        Route::patch('orders/{orderId}/items/{itemId}', [OrderController::class, 'updateItem'])->name('orders.items.update');
        Route::delete('orders/{orderId}/items/{itemId}', [OrderController::class, 'removeItem'])->name('orders.items.remove');
    });
