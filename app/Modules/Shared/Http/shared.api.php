<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Http\Controllers\InfrastructureController;
use App\Modules\Shared\Http\Controllers\PersonController;
use App\Modules\Shared\Http\Controllers\ProfileController;

Route::middleware(['auth:api'])->prefix('/infrastructures')->group(function () {
    Route::get('/select-items', [InfrastructureController::class, 'selectItems'])->name('infrastructures.selectItems');
    Route::get('/items', [InfrastructureController::class, 'items'])->name('infrastructures.items');
});

Route::middleware(['auth:api'])->prefix('/persons')->group(function () {
    Route::get('/select-async-items', [PersonController::class, 'selectAsyncItems'])->name('persons.selectAsyncItems');
    Route::get('/search-by-document', [PersonController::class, 'searchByDocument'])->name('persons.searchByDocument');
});

Route::middleware(['auth:api'])->prefix('/profile')->group(function () {
    Route::get('/me',               [ProfileController::class, 'me']);
    Route::put('/update-personal',  [ProfileController::class, 'updatePersonal']);
    Route::put('/change-password',  [ProfileController::class, 'changePassword']);
});
