<?php

use App\Modules\Learning\Instructor\Catalog\Course\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/courses')->group(function () {
    Route::post('/data-table', [CourseController::class, 'dataTable']);
    Route::get('/get/{id}', [CourseController::class, 'getById']);
    Route::post('/save', [CourseController::class, 'save']);
    Route::delete('/delete/{id}', [CourseController::class, 'delete']);
    Route::get('/select-items', [CourseController::class, 'getSelectItems']);
});
