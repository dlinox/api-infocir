<?php

use Illuminate\Support\Facades\Route;
use App\Modules\CollectorPanel\CollectionRoute\Http\Controllers\CollectionRouteController;

Route::middleware(['auth:api', 'check.session'])->prefix('/collector-panel/routes')->group(function () {
    Route::get('/active', [CollectionRouteController::class, 'active'])->name('collectorPanel.routes.active');
    Route::get('/stats', [CollectionRouteController::class, 'stats'])->name('collectorPanel.routes.stats');
    Route::get('/expense-items', [CollectionRouteController::class, 'expenseItems'])->name('collectorPanel.routes.expenseItems');
    Route::post('/start', [CollectionRouteController::class, 'start'])->name('collectorPanel.routes.start');
    Route::post('/{routeId}/finalize', [CollectionRouteController::class, 'finalize'])->name('collectorPanel.routes.finalize');
    Route::post('/data-table', [CollectionRouteController::class, 'dataTable'])->name('collectorPanel.routes.dataTable');
});
