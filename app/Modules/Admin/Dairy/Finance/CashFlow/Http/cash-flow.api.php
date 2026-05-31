<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Finance\CashFlow\Http\Controllers\CashFlowController;

Route::middleware(['auth:api', 'check.session'])->prefix('/cash-flow')->group(function () {
    Route::get('/overview', [CashFlowController::class, 'overview'])->name('cashFlow.overview');
});
