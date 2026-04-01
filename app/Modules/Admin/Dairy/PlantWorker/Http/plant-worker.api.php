<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\PlantWorker\Http\Controllers\PlantWorkerController;

Route::middleware(['auth:api'])->prefix('/plant-workers')->group(function () {
    Route::post('/data-table', [PlantWorkerController::class, 'dataTable'])->name('plant-workers.dataTable');
    Route::get('/get/{id}', [PlantWorkerController::class, 'getById'])->name('plant-workers.getById');
    Route::post('/save', [PlantWorkerController::class, 'save'])->name('plant-workers.save');
    Route::delete('/delete/{id}', [PlantWorkerController::class, 'delete'])->name('plant-workers.delete');
});
