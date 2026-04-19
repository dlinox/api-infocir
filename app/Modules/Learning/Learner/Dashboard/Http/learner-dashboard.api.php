<?php

use App\Modules\Learning\Learner\Dashboard\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('/learner/dashboard')->group(function () {
    Route::get('/stats', [DashboardController::class, 'stats']);
});
