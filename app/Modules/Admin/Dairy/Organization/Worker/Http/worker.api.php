<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Organization\Worker\Http\Controllers\WorkerController;

Route::middleware(['auth:api'])->prefix('/workers')->group(function () {
    Route::post('/data-table', [WorkerController::class, 'dataTable'])->name('workers.dataTable');
    Route::get('/get/{id}', [WorkerController::class, 'getById'])->name('workers.getById');
    Route::post('/save', [WorkerController::class, 'save'])->name('workers.save');
    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])->name('workers.delete');
});
