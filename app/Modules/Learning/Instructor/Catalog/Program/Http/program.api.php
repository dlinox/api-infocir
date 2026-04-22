<?php

use App\Modules\Learning\Instructor\Catalog\Program\Http\Controllers\ProgramController;
use App\Modules\Learning\Instructor\Catalog\Program\Http\Controllers\ProgramCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learning/instructor/programs')->group(function () {
    Route::post('/data-table', [ProgramController::class, 'dataTable']);
    Route::get('/get/{id}', [ProgramController::class, 'getById']);
    Route::post('/save', [ProgramController::class, 'save']);
    Route::delete('/delete/{id}', [ProgramController::class, 'delete']);
    Route::get('/select-items', [ProgramController::class, 'getSelectItems']);

    Route::prefix('/courses')->group(function () {
        Route::post('/save', [ProgramCourseController::class, 'save']);
        Route::delete('/delete/{id}', [ProgramCourseController::class, 'delete']);
    });
});
