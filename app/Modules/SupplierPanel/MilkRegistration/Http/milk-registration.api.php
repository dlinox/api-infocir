<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierPanel\MilkRegistration\Http\Controllers\MilkRegistrationController;

Route::middleware(['auth:api'])->prefix('/supplier-panel/milk-registrations')->group(function () {
    Route::post('/data-table', [MilkRegistrationController::class, 'dataTable'])->name('supplierPanel.milkRegistrations.dataTable');
    Route::get('/get/{id}', [MilkRegistrationController::class, 'getById'])->name('supplierPanel.milkRegistrations.getById');
    Route::post('/save', [MilkRegistrationController::class, 'save'])->name('supplierPanel.milkRegistrations.save');
    Route::delete('/delete/{id}', [MilkRegistrationController::class, 'delete'])->name('supplierPanel.milkRegistrations.delete');
});
