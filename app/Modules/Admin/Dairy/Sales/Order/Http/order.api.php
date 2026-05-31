<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Sales\Order\Http\Controllers\OrderController;

Route::middleware(['auth:api', 'check.session'])->prefix('/orders')->group(function () {
    Route::post('/data-table', [OrderController::class, 'dataTable'])->name('orders.dataTable');
    Route::get('/get/{id}', [OrderController::class, 'getById'])->name('orders.getById');
    Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::get('/presentations/{id}', [OrderController::class, 'presentations'])->name('orders.presentations');
    Route::post('/items/{id}', [OrderController::class, 'addItem'])->name('orders.addItem');
    Route::post('/items/{id}/{itemId}', [OrderController::class, 'updateItem'])->name('orders.updateItem');
    Route::delete('/items/{id}/{itemId}', [OrderController::class, 'removeItem'])->name('orders.removeItem');
    Route::get('/receipt/{id}', [OrderController::class, 'receipt'])->name('orders.receipt');
});
