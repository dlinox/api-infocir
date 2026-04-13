<?php

namespace App\Modules\Auth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

use App\Modules\Auth\Http\Requests\SignInRequest;
use App\Modules\Auth\Http\Resources\SignInResource;
use App\Modules\Auth\Http\Resources\ProfileResource;
use App\Modules\Auth\Services\AuthService;
use App\Common\Http\Responses\ApiResponse;


class AuthController
{
    public function __construct(
        private AuthService $authService
    ) {}

    /**
     * Sign in with username or email
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        $req = $request->validated();
        $result = $this->authService->signIn(
            $req['identifier'],
            $req['password'],
            $request
        );
        if (!$result) return ApiResponse::unauthorized('Credenciales invalidas');
        return ApiResponse::success((new SignInResource($result)), 'Inicio de sesión exitoso');
    }
    /**
     * Get authenticated user with profile data
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();
        return ApiResponse::success($user, '');
    }
    /**
     * Get active profiles for authenticated user
     */
    public function profiles(): JsonResponse
    {
        $profiles = $this->authService->getProfiles();
        return ApiResponse::success(ProfileResource::collection($profiles), '');
    }
    /**
     * Select a profile and get new token
     */
    public function selectProfile(int $profileId): JsonResponse
    {
        $result = $this->authService->selectProfile($profileId);
        return ApiResponse::success((new SignInResource($result)), 'Perfil seleccionado exitosamente');
    }
    /**
     * Refresh token
     */
    public function refresh(): JsonResponse
    {
        $token = $this->authService->refresh();
        return ApiResponse::success($token, '');
    }
    /**
     * Sign out (invalidate token)
     */
    public function signOut(): JsonResponse
    {
        $this->authService->signOut();
        return ApiResponse::success(null, '');
    }

    /**
     * Get the entity (plant or supplier) assigned to the current worker profile
     */
    public function myEntity(): JsonResponse
    {
        $entity = $this->authService->getMyEntity();
        return ApiResponse::success($entity, '');
    }

    /**
     * Redirect to Google OAuth
     */
    public function googleRedirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function googleCallback(Request $request): JsonResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $result = $this->authService->signInWithGoogle($googleUser->getEmail(), $request);
        return ApiResponse::success((new SignInResource($result)), 'Inicio de sesión con Google exitoso');
    }
}
