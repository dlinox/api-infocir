<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\ProductionBatch\Http\Controllers\ProductionBatchController;

Route::middleware(['auth:api'])->prefix('/plant-panel/production-batches')->group(function () {
    Route::post('/data-table', [ProductionBatchController::class, 'dataTable'])->name('plantPanel.productionBatches.dataTable');
    Route::get('/get/{id}', [ProductionBatchController::class, 'getById'])->name('plantPanel.productionBatches.getById');
    Route::post('/save', [ProductionBatchController::class, 'save'])->name('plantPanel.productionBatches.save');
    Route::delete('/delete/{id}', [ProductionBatchController::class, 'delete'])->name('plantPanel.productionBatches.delete');
    Route::get('/supplier-select-items', [ProductionBatchController::class, 'supplierSelectItems'])->name('plantPanel.productionBatches.supplierSelectItems');
    Route::patch('/cancel/{id}', [ProductionBatchController::class, 'cancel'])->name('plantPanel.productionBatches.cancel');
    Route::patch('/mark-ready/{id}', [ProductionBatchController::class, 'markReady'])->name('plantPanel.productionBatches.markReady');
});
