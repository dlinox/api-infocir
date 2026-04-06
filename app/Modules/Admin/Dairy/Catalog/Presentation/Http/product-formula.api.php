<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Dairy\Catalog\Presentation\Http\Controllers\ProductFormulaController;

Route::middleware(['auth:api'])->prefix('/product-formulas')->group(function () {
    Route::get('/by-presentation/{presentationId}', [ProductFormulaController::class, 'getByPresentation'])
        ->name('productFormulas.getByPresentation');
    Route::get('/versions/{presentationId}', [ProductFormulaController::class, 'getVersions'])
        ->name('productFormulas.getVersions');
    Route::post('/save', [ProductFormulaController::class, 'save'])
        ->name('productFormulas.save');
    Route::post('/create-version/{presentationId}', [ProductFormulaController::class, 'createVersion'])
        ->name('productFormulas.createVersion');
    Route::delete('/delete/{id}', [ProductFormulaController::class, 'delete'])
        ->name('productFormulas.delete');
});
