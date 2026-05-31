<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Finance\BusinessPlan\Http\Controllers\PlantBusinessPlanController;

Route::middleware(['auth:api', 'check.session'])->prefix('/plants/{plantId}/business-plan')->group(function () {
    Route::get('/', [PlantBusinessPlanController::class, 'show'])->name('businessPlan.show');
    Route::post('/', [PlantBusinessPlanController::class, 'save'])->name('businessPlan.save');
});
