<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Organization\Plant\Http\Controllers\PlantController;
use App\Modules\Admin\Dairy\Organization\Plant\Http\Controllers\PlantGalleryController;
use App\Modules\Admin\Dairy\Organization\Plant\Http\Controllers\PlantSupplierController;

Route::middleware(['auth:api'])->prefix('/plants')->group(function () {
    Route::post('/data-table', [PlantController::class, 'dataTable'])->name('plants.dataTable');
    Route::get('/get/{id}', [PlantController::class, 'getById'])->name('plants.getById');
    Route::post('/save', [PlantController::class, 'save'])->name('plants.save');
    Route::delete('/delete/{id}', [PlantController::class, 'delete'])->name('plants.delete');
    Route::get('/select-items', [PlantController::class, 'getSelectItems'])->name('plants.selectItems');
});

Route::middleware(['auth:api'])->prefix('/plant-galleries')->group(function () {
    Route::post('/data-table', [PlantGalleryController::class, 'dataTable'])->name('plant-galleries.dataTable');
    Route::post('/save', [PlantGalleryController::class, 'save'])->name('plant-galleries.save');
    Route::delete('/delete/{id}', [PlantGalleryController::class, 'delete'])->name('plant-galleries.delete');
});

Route::middleware(['auth:api'])->prefix('/plants/{plantId}/suppliers')->group(function () {
    Route::get('/assigned', [PlantSupplierController::class, 'getAssigned'])->name('plants.suppliers.getAssigned');
    Route::post('/sync', [PlantSupplierController::class, 'sync'])->name('plants.suppliers.sync');
});
