<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierPanel\MilkDelivery\Http\Controllers\MilkDeliveryController;

Route::middleware(['auth:api'])->prefix('/supplier-panel/milk-deliveries')->group(function () {
    Route::post('/data-table', [MilkDeliveryController::class, 'dataTable'])->name('supplierPanel.milkDeliveries.dataTable');
});
