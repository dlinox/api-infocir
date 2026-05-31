<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierPanel\CashFlow\Http\Controllers\SupplierCashFlowController;

Route::middleware(['auth:api', 'check.session'])->prefix('/supplier-panel/cash-flow')->group(function () {
    Route::get('/overview', [SupplierCashFlowController::class, 'overview'])->name('supplierPanel.cashFlow.overview');
});
