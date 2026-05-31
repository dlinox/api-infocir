<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Order\Http\Controllers\PlantOrderController;

Route::middleware(['auth:api', 'check.session'])->prefix('/plant-panel/orders')->group(function () {
    Route::post('/data-table', [PlantOrderController::class, 'dataTable'])->name('plantPanel.orders.dataTable');
    Route::get('/get/{id}', [PlantOrderController::class, 'getById'])->name('plantPanel.orders.getById');
    Route::post('/update-status/{id}', [PlantOrderController::class, 'updateStatus'])->name('plantPanel.orders.updateStatus');
    Route::get('/presentations/{id}', [PlantOrderController::class, 'presentations'])->name('plantPanel.orders.presentations');
    Route::post('/items/{id}', [PlantOrderController::class, 'addItem'])->name('plantPanel.orders.addItem');
    Route::post('/items/{id}/{itemId}', [PlantOrderController::class, 'updateItem'])->name('plantPanel.orders.updateItem');
    Route::delete('/items/{id}/{itemId}', [PlantOrderController::class, 'removeItem'])->name('plantPanel.orders.removeItem');
    Route::get('/receipt/{id}', [PlantOrderController::class, 'receipt'])->name('plantPanel.orders.receipt');
});
