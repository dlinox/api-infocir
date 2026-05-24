<?php

use App\Modules\SupplierPanel\Dashboard\Http\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.session'])->group(function () {
    Route::get('/supplier-panel/dashboard/summary', [DashboardController::class, 'summary']);
});
