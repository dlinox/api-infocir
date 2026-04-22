<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Catalog\Http\Controllers\PlantCatalogProductController;

Route::middleware(['auth:api'])->prefix('/plant-panel/catalog')->group(function () {
    Route::get('/plant-products', [PlantCatalogProductController::class, 'plantProducts'])->name('plantPanel.catalog.plantProducts');
    Route::get('/available-products', [PlantCatalogProductController::class, 'availableProducts'])->name('plantPanel.catalog.availableProducts');
    Route::post('/add-product', [PlantCatalogProductController::class, 'addProduct'])->name('plantPanel.catalog.addProduct');
    Route::post('/create-and-add', [PlantCatalogProductController::class, 'createAndAdd'])->name('plantPanel.catalog.createAndAdd');
    Route::post('/save-presentation', [PlantCatalogProductController::class, 'savePresentation'])->name('plantPanel.catalog.savePresentation');
});
