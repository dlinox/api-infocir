<?php

use Illuminate\Support\Facades\Route;
use App\Modules\SupplierPanel\Setting\Http\Controllers\SettingController;

Route::middleware(['auth:api'])->prefix('/supplier-panel/setting')->group(function () {
    Route::get('/get', [SettingController::class, 'getCurrent'])->name('supplierPanel.setting.get');
    Route::post('/save', [SettingController::class, 'save'])->name('supplierPanel.setting.save');
});
