<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers\PlantProductController;

Route::middleware(['auth:api'])->prefix('/plant-products')->group(function () {
    Route::post('/data-table', [PlantProductController::class, 'dataTable'])->name('plantProducts.dataTable');
    Route::get('/get/{id}', [PlantProductController::class, 'get'])->name('plantProducts.get');
    Route::get('/list', [PlantProductController::class, 'list'])->name('plantProducts.list');
    Route::get('/by-plant/{plantId}', [PlantProductController::class, 'getByPlant'])->name('plantProducts.getByPlant');
    Route::get('/select-items/{plantId}', [PlantProductController::class, 'getSelectItems'])->name('plantProducts.getSelectItems');
    Route::post('/sync', [PlantProductController::class, 'sync'])->name('plantProducts.sync');
});
