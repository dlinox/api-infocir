<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Organization\Supplier\Http\Controllers\SupplierController;

Route::middleware(['auth:api'])->prefix('/suppliers')->group(function () {
    Route::post('/data-table', [SupplierController::class, 'dataTable'])->name('suppliers.dataTable');
    Route::get('/get/{id}', [SupplierController::class, 'getById'])->name('suppliers.getById');
    Route::post('/save', [SupplierController::class, 'save'])->name('suppliers.save');
    Route::delete('/delete/{id}', [SupplierController::class, 'delete'])->name('suppliers.delete');
});
