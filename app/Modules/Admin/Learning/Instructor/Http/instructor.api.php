<?php

use App\Modules\Admin\Learning\Instructor\Http\Controllers\InstructorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.session'])->prefix('/learning/instructors')->group(function () {
    Route::post('/data-table', [InstructorController::class, 'dataTable'])->name('instructors.dataTable');
    Route::get('/get/{id}', [InstructorController::class, 'getById'])->name('instructors.getById');
    Route::post('/save', [InstructorController::class, 'save'])->name('instructors.save');
    Route::delete('/delete/{id}', [InstructorController::class, 'delete'])->name('instructors.delete');
    Route::get('/select-items', [InstructorController::class, 'getSelectItems'])->name('instructors.selectItems');
});
