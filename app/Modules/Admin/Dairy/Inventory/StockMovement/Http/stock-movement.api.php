<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Inventory\StockMovement\Http\Controllers\StockMovementController;

Route::middleware(['auth:api'])->prefix('/stock-movements')->group(function () {
    Route::post('/data-table', [StockMovementController::class, 'dataTable'])
        ->name('stockMovements.dataTable');
    Route::get('/summary/{presentationId}', [StockMovementController::class, 'summary'])
        ->name('stockMovements.summary');
    Route::post('/save', [StockMovementController::class, 'save'])
        ->name('stockMovements.save');
    Route::delete('/delete/{id}', [StockMovementController::class, 'delete'])
        ->name('stockMovements.delete');
});
