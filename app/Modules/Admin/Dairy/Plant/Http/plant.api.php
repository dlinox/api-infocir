<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Plant\Http\Controllers\PlantController;

Route::middleware(['auth:api'])->prefix('/plants')->group(function () {
    Route::post('/data-table', [PlantController::class, 'dataTable'])->name('plants.dataTable');
    Route::get('/get/{id}', [PlantController::class, 'getById'])->name('plants.getById');
    Route::post('/save', [PlantController::class, 'save'])->name('plants.save');
    Route::delete('/delete/{id}', [PlantController::class, 'delete'])->name('plants.delete');
    Route::get('/select-items', [PlantController::class, 'getSelectItems'])->name('plants.selectItems');
});
