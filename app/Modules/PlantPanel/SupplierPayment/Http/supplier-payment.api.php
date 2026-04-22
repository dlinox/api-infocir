<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\SupplierPayment\Http\Controllers\SupplierPaymentController;

Route::middleware(['auth:api'])->prefix('/plant-panel/supplier-payments')->group(function () {
    Route::post('/data-table', [SupplierPaymentController::class, 'dataTable'])->name('plantPanel.supplierPayments.dataTable');
    Route::get('/get/{id}', [SupplierPaymentController::class, 'getById'])->name('plantPanel.supplierPayments.getById');
    Route::post('/save', [SupplierPaymentController::class, 'save'])->name('plantPanel.supplierPayments.save');
    Route::delete('/delete/{id}', [SupplierPaymentController::class, 'delete'])->name('plantPanel.supplierPayments.delete');
    Route::get('/summarize-period', [SupplierPaymentController::class, 'summarizePeriod'])->name('plantPanel.supplierPayments.summarizePeriod');
    Route::get('/supplier-select-items', [SupplierPaymentController::class, 'supplierSelectItems'])->name('plantPanel.supplierPayments.supplierSelectItems');
    Route::patch('/mark-as-paid/{id}', [SupplierPaymentController::class, 'markAsPaid'])->name('plantPanel.supplierPayments.markAsPaid');
    Route::patch('/cancel/{id}', [SupplierPaymentController::class, 'cancel'])->name('plantPanel.supplierPayments.cancel');
});
