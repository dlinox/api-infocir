<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Supplier\Http\Controllers\PlantPanelSupplierController;

Route::middleware(['auth:api'])->prefix('/plant-panel/my-suppliers')->group(function () {
    Route::get('/',                          [PlantPanelSupplierController::class, 'list']);
    Route::patch('/{supplierId}/toggle',     [PlantPanelSupplierController::class, 'toggleActive']);
    Route::patch('/{supplierId}/price',      [PlantPanelSupplierController::class, 'updatePrice']);
});
