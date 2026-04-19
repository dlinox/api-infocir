<?php

use App\Modules\Learning\Instructor\Scheduling\Training\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/trainings')->group(function () {
    Route::post('/data-table', [TrainingController::class, 'dataTable']);
    Route::get('/get/{id}', [TrainingController::class, 'getById']);
    Route::post('/save', [TrainingController::class, 'save']);
    Route::delete('/delete/{id}', [TrainingController::class, 'delete']);
});
