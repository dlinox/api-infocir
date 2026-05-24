<?php

use Illuminate\Support\Facades\Route;
use App\Modules\PlantPanel\Investment\Http\Controllers\InvestmentPlanController;
use App\Modules\PlantPanel\Investment\Http\Controllers\FixedAssetController;
use App\Modules\PlantPanel\Investment\Http\Controllers\PreOperativeExpenseController;

Route::middleware(['auth:api', 'check.session'])->group(function () {

    // Capital de Trabajo (gastos mensuales)
    Route::prefix('/investment-plan')->group(function () {
        Route::get('/working-capital/{year}/{month}',      [InvestmentPlanController::class, 'getWorkingCapital']);
        Route::post('/save',                               [InvestmentPlanController::class, 'save']);
        Route::get('/working-capital-workers',             [InvestmentPlanController::class, 'getWorkingCapitalWorkers']);
        Route::get('/summary',                             [InvestmentPlanController::class, 'getSummary']);
        Route::post('/copy-previous-month/{year}/{month}', [InvestmentPlanController::class, 'copyPreviousMonth']);
    });

    // Activos Fijos (CRUD acumulativo)
    Route::prefix('/fixed-assets')->group(function () {
        Route::post('/data-table',    [FixedAssetController::class, 'dataTable']);
        Route::get('/get/{id}',       [FixedAssetController::class, 'get']);
        Route::post('/save',          [FixedAssetController::class, 'save']);
        Route::delete('/delete/{id}', [FixedAssetController::class, 'delete']);
    });

    // Permisos y Licencias (CRUD acumulativo)
    Route::prefix('/pre-operative-expenses')->group(function () {
        Route::post('/data-table',    [PreOperativeExpenseController::class, 'dataTable']);
        Route::get('/get/{id}',       [PreOperativeExpenseController::class, 'get']);
        Route::post('/save',          [PreOperativeExpenseController::class, 'save']);
        Route::delete('/delete/{id}', [PreOperativeExpenseController::class, 'delete']);
    });
});