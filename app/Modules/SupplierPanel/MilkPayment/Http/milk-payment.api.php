<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierPanel\MilkPayment\Http\Controllers\MilkPaymentController;

Route::middleware(['auth:api', 'check.session'])->prefix('/supplier-panel/milk-payments')->group(function () {
    Route::post('/data-table', [MilkPaymentController::class, 'dataTable'])->name('supplierPanel.milkPayments.dataTable');
});
