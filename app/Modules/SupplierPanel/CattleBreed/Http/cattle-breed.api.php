<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierPanel\CattleBreed\Http\Controllers\CattleBreedController;

Route::middleware(['auth:api'])->prefix('/supplier-panel/cattle-breeds')->group(function () {
    Route::post('/data-table', [CattleBreedController::class, 'dataTable'])->name('supplierPanel.cattleBreeds.dataTable');
    Route::get('/get/{id}', [CattleBreedController::class, 'getById'])->name('supplierPanel.cattleBreeds.getById');
    Route::post('/save', [CattleBreedController::class, 'save'])->name('supplierPanel.cattleBreeds.save');
    Route::delete('/delete/{id}', [CattleBreedController::class, 'delete'])->name('supplierPanel.cattleBreeds.delete');
});
