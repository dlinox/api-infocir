<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Investment\Http\Controllers\InvestmentPlanController;

Route::middleware(['auth:api'])->prefix('/investment-plan')->group(function () {
    Route::get('/current',         [InvestmentPlanController::class, 'getCurrent']);
    Route::post('/save',           [InvestmentPlanController::class, 'save']);
    Route::post('/approve/{id}',   [InvestmentPlanController::class, 'approve']);
});
