<?php

use App\Modules\Auth\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

// Public routes
Route::post('auth/sign-in', [AuthController::class, 'signIn']);
Route::get('auth/google', [AuthController::class, 'googleRedirect']);
Route::get('auth/google/callback', [AuthController::class, 'googleCallback']);

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('auth/profiles', [AuthController::class, 'profiles']);
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::get('auth/my-entity', [AuthController::class, 'myEntity']);
    Route::post('auth/select-profile/{profileId}', [AuthController::class, 'selectProfile']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::post('auth/sign-out', [AuthController::class, 'signOut']);

    // DEBUG: endpoint temporal para ver qué app tiene el token
    Route::get('auth/debug-token', function () {
        $payload = JWTAuth::parseToken()->getPayload();
        return response()->json([
            'app' => $payload->get('app'),
            'prf' => $payload->get('prf'),
            'modules_config' => config('app.modules'),
        ]);
    });

    Route::get('auth/test-permissions', function () {
        return response()->json([
            'message' => 'Test',
        ]);
    })->middleware('permission:view_users,manage_users');
});
