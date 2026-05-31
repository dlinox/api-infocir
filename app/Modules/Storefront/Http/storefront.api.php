<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Storefront\Http\Controllers\StorefrontCatalogController;
use App\Modules\Storefront\Http\Controllers\StorefrontOrderController;

Route::prefix('/storefront')->group(function () {
    Route::get('/categories', [StorefrontCatalogController::class, 'categories'])->name('storefront.categories');
    Route::get('/products', [StorefrontCatalogController::class, 'products'])->name('storefront.products');
    Route::get('/products/{id}', [StorefrontCatalogController::class, 'productById'])->name('storefront.productById');
    Route::get('/plants', [StorefrontCatalogController::class, 'plants'])->name('storefront.plants');
    Route::get('/plants/{slug}', [StorefrontCatalogController::class, 'plantBySlug'])->name('storefront.plantBySlug');
    Route::get('/suppliers', [StorefrontCatalogController::class, 'suppliers'])->name('storefront.suppliers');
    Route::post('/orders', [StorefrontOrderController::class, 'store'])->name('storefront.orders.store');
});
