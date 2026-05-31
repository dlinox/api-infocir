<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Onboarding\Http\Controllers\OnboardingController;

Route::middleware(['auth:api', 'check.session'])->prefix('/plant-panel/onboarding')->group(function () {
    Route::get('/fixed-assets-catalog', [OnboardingController::class, 'getFixedAssetsCatalog'])
        ->name('plant-panel.onboarding.fixedAssetsCatalog');
    Route::get('/products-catalog', [OnboardingController::class, 'getProductsCatalog'])
        ->name('plant-panel.onboarding.productsCatalog');
    Route::post('/complete', [OnboardingController::class, 'complete'])
        ->name('plant-panel.onboarding.complete');
});
