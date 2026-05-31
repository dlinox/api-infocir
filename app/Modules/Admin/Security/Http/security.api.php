<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Security\Http\Controllers\RoleController;
use App\Modules\Admin\Security\Http\Controllers\PermissionController;
use App\Modules\Admin\Security\Http\Controllers\UserController;
use App\Modules\Admin\Security\Http\Controllers\SessionController;

Route::middleware(['auth:api', 'check.session'])->prefix('/roles')->group(function () {
    Route::post('/data-table', [RoleController::class, 'dataTable'])->name('roles.dataTable');
    Route::get('/get/{id}', [RoleController::class, 'get'])->name('roles.get');
    Route::post('/save', [RoleController::class, 'save'])->name('roles.save');
    Route::delete('/delete/{id}', [RoleController::class, 'delete'])->name('roles.delete');
});

Route::middleware(['auth:api', 'check.session'])->prefix('/permissions')->group(function () {
    Route::post('/data-table', [PermissionController::class, 'dataTable'])->name('permissions.dataTable');
    Route::get('/get/{id}', [PermissionController::class, 'get'])->name('permissions.get');
    Route::post('/save', [PermissionController::class, 'save'])->name('permissions.save');
    Route::delete('/delete/{id}', [PermissionController::class, 'delete'])->name('permissions.delete');
    Route::get('/select-items', [PermissionController::class, 'getSelectItems'])->name('permissions.selectItems');
});

Route::middleware(['auth:api', 'check.session'])->prefix('/users')->group(function () {
    Route::post('/data-table', [UserController::class, 'dataTable'])->name('users.dataTable');
    Route::get('/get/{id}', [UserController::class, 'get'])->name('users.get');
    Route::post('/save', [UserController::class, 'save'])->name('users.save');
    Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::post('/{id}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
    Route::post('/{id}/profiles', [UserController::class, 'assignProfiles'])->name('users.assignProfiles');
    Route::get('/{id}/core-profiles', [UserController::class, 'coreProfiles'])->name('users.coreProfiles');
    Route::get('/{id}/sessions', [UserController::class, 'sessions'])->name('users.sessions');
    Route::post('/{id}/revoke-all-sessions', [UserController::class, 'revokeAllSessions'])->name('users.revokeAllSessions');
});

Route::middleware(['auth:api', 'check.session'])->prefix('/sessions')->group(function () {
    Route::post('/data-table', [SessionController::class, 'dataTable'])->name('sessions.dataTable');
    Route::delete('/{id}/revoke', [SessionController::class, 'revoke'])->name('sessions.revoke');
});
