<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Worker\Http\Controllers\PlantWorkerController;

Route::middleware(['auth:api'])->prefix('/plant-panel/workers')->group(function () {
    Route::post('/data-table', [PlantWorkerController::class, 'dataTable'])->name('plant-panel.workers.dataTable');
    Route::get('/get/{id}', [PlantWorkerController::class, 'getById'])->name('plant-panel.workers.getById');
    Route::post('/save', [PlantWorkerController::class, 'save'])->name('plant-panel.workers.save');
    Route::delete('/delete/{id}', [PlantWorkerController::class, 'delete'])->name('plant-panel.workers.delete');
});
