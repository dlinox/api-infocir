<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Catalog\Product\Http\Controllers\ProductController;

Route::middleware(['auth:api'])->prefix('/products')->group(function () {
    Route::post('/data-table', [ProductController::class, 'dataTable'])->name('products.dataTable');
    Route::get('/get/{id}', [ProductController::class, 'getById'])->name('products.getById');
    Route::post('/save', [ProductController::class, 'save'])->name('products.save');
    Route::delete('/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');
    Route::get('/select-items', [ProductController::class, 'getSelectItems'])->name('products.selectItems');
});
