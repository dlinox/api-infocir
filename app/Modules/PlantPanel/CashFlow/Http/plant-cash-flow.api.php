<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\CashFlow\Http\Controllers\PlantCashFlowController;

Route::middleware(['auth:api', 'check.session'])->prefix('/plant-panel/cash-flow')->group(function () {
    Route::get('/overview', [PlantCashFlowController::class, 'overview'])->name('plantPanel.cashFlow.overview');
});
