<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Shared\Http\Controllers\InfrastructureController;
use App\Modules\Shared\Http\Controllers\PersonController;
use App\Modules\Shared\Http\Controllers\ProfileController;
use App\Modules\Shared\Http\Controllers\RolesController;

Route::middleware(['auth:api'])->prefix('/entities')->group(function () {
    Route::get('/select-items', [InfrastructureController::class, 'selectItems'])->name('entities.selectItems');
    Route::get('/items', [InfrastructureController::class, 'items'])->name('entities.items');
});

Route::middleware(['auth:api'])->prefix('/roles')->group(function () {
    Route::get('/select-items', [RolesController::class, 'selectItems'])->name('roles.selectItems');
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
