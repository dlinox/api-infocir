<?php

use App\Modules\Admin\Learning\Instructor\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learning/instructors')->group(function () {
    Route::post('/data-table', [InstructorController::class, 'dataTable']);
    Route::get('/get/{id}', [InstructorController::class, 'getById']);
    Route::post('/save', [InstructorController::class, 'save']);
    Route::delete('/delete/{id}', [InstructorController::class, 'delete']);
    Route::get('/select-items', [InstructorController::class, 'getSelectItems']);
});
