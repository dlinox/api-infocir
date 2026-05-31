<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['auth:api', 'check.session'])->prefix('/admin/dashboard')->group(function () {
    Route::get('/summary', [DashboardController::class, 'summary'])->name('dashboard.summary');
    Route::get('/map-data', [DashboardController::class, 'mapData'])->name('dashboard.mapData');
    Route::get('/overview', [DashboardController::class, 'overview'])->name('dashboard.overview');
});
