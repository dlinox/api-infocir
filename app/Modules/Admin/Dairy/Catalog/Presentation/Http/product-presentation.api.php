<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers\ProductPresentationController;

Route::middleware(['auth:api'])->prefix('/product-presentations')->group(function () {
    Route::post('/data-table', [ProductPresentationController::class, 'dataTable'])->name('productPresentations.dataTable');
    Route::get('/get/{id}', [ProductPresentationController::class, 'getById'])->name('productPresentations.getById');
    Route::post('/save', [ProductPresentationController::class, 'save'])->name('productPresentations.save');
    Route::delete('/delete/{id}', [ProductPresentationController::class, 'delete'])->name('productPresentations.delete');
    Route::get('/select-items/{plantProductId}', [ProductPresentationController::class, 'getSelectItems'])->name('productPresentations.selectItems');
});
