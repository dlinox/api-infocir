<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\MilkCollection\Http\Controllers\MilkCollectionController;

Route::middleware(['auth:api'])->prefix('/plant-panel/milk-collections')->group(function () {
    Route::post('/data-table', [MilkCollectionController::class, 'dataTable'])->name('plantPanel.milkCollections.dataTable');
    Route::get('/get/{id}', [MilkCollectionController::class, 'getById'])->name('plantPanel.milkCollections.getById');
    Route::post('/save', [MilkCollectionController::class, 'save'])->name('plantPanel.milkCollections.save');
    Route::delete('/delete/{id}', [MilkCollectionController::class, 'delete'])->name('plantPanel.milkCollections.delete');
    Route::get('/supplier-select-items', [MilkCollectionController::class, 'supplierSelectItems'])->name('plantPanel.milkCollections.supplierSelectItems');
    Route::patch('/update-payment-status/{id}', [MilkCollectionController::class, 'updatePaymentStatus'])->name('plantPanel.milkCollections.updatePaymentStatus');
});
