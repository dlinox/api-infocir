<?php

use App\Modules\Learning\Instructor\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api', 'check.session'])->prefix('/learning/instructor/dashboard')->group(function () {
    Route::get('/stats', [DashboardController::class, 'stats']);
});
