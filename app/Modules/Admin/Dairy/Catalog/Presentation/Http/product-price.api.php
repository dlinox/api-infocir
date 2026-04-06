<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers\ProductPriceController;

Route::middleware(['auth:api'])->prefix('/product-prices')->group(function () {
    Route::get('/by-presentation/{presentationId}', [ProductPriceController::class, 'getByPresentation'])
        ->name('productPrices.getByPresentation');
    Route::post('/save', [ProductPriceController::class, 'save'])
        ->name('productPrices.save');
    Route::delete('/delete/{id}', [ProductPriceController::class, 'delete'])
        ->name('productPrices.delete');
});
