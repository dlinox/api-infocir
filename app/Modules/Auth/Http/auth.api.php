<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('auth/sign-in', [AuthController::class, 'signIn']);
Route::get('auth/google', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('auth/profiles', [AuthController::class, 'profiles']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/select-profile/{profileId}', [AuthController::class, 'selectProfile']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::post('auth/sign-out', [AuthController::class, 'signOut']);

    Route::get('auth/test-permissions', function () {
        return response()->json([
            'message' => 'Test',
        ]);
    })->middleware('permission:view_users,manage_users');
});
