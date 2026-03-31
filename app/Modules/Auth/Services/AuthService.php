<?php

namespace App\Modules\Auth\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Common\Exceptions\ApiException;

use App\Modules\Auth\Repositories\SessionRepository;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Auth\Repositories\Queries\MeQuery;

use App\Modules\Auth\Repositories\ProfileRepository;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionRepository $sessionRepository,
        private ProfileRepository $profileRepository,
        private MeQuery $meQuery
    ) {}

    /**
     * Authenticate user by username or email
     * Returns [token, profilesCount, hasProfile]
     */
    public function signIn(string $identifier, string $password, Request $request): ?object
    {
        $user = $this->userRepository->findByIdentifier($identifier);

        if (!$user) throw new ApiException("El usuario no existe", 401);
        if (!$this->userRepository->isActive($user))  throw new ApiException("El usuario no esta activo", 401);
        if (!Hash::check($password, $user->password)) throw new ApiException("Credenciales invalidas", 401);


        // Check active profiles
        $profilesCount = $this->profileRepository->countByUserId($user->id);

        // If only one profile, auto-select it
        $profileId = null;
        $me = null;
        if ($profilesCount === 1) {
            $me = ($this->meQuery)($user, null);
            $profileId = $me['profile']['id'];
        }

        $token = JWTAuth::claims([
            'prf' => $profileId,
        ])->fromUser($user);

        // Create session
        $this->userRepository->updateLastSignIn($user);
        $this->sessionRepository->create(
            $user->id,
            $profileId,
            $request->ip(),
            $request->userAgent()
        );

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }

    /**
     * Get active profiles for user
     */
    public function getProfiles(): Collection
    {
        $user = JWTAuth::user();
        $profiles = $this->profileRepository->getByUserId($user->id);
        return $profiles;
    }

    /**
     * Select a profile and generate new token
     */
    public function selectProfile(int $profileId): object
    {
        $profile = $this->profileRepository->findById($profileId);

        if (!$profile) throw new ApiException("Perfil no encontrado", 404);

        $user = JWTAuth::user();

        $me = ($this->meQuery)($user, $profileId);

        JWTAuth::invalidate(JWTAuth::getToken());

        $token = JWTAuth::claims([
            'prf' => $profileId,
        ])->fromUser($user);

        $this->sessionRepository->updateProfile($user->id, $profileId);

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }

    /**
     * Refresh the JWT token
     */
    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    /**
     * Sign out (invalidate token and sessions)
     */
    public function signOut(): void
    {
        $user = JWTAuth::user();

        if ($user) {
            $this->sessionRepository->invalidateAllForUser($user->id);
        }

        JWTAuth::invalidate(JWTAuth::getToken());
    }

    /**
     * Get authenticated user
     */
    public function me()
    {
        $user = JWTAuth::user();
        $profileId = $this->getProfileIdFromToken();
        if (!$profileId) throw new ApiException("No se encontro el perfil", 401);
        return ($this->meQuery)($user, $profileId);
    }

    /**
     * Get profile ID from JWT claims
     */
    public function getProfileIdFromToken(): ?int
    {
        $payload = JWTAuth::parseToken()->getPayload();
        return $payload->get('prf');
    }

    /**
     * Authenticate user via Google OAuth (by email)
     */
    public function signInWithGoogle(string $email, Request $request): ?object
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) throw new ApiException("No existe cuenta con este email", 401);
        if (!$this->userRepository->isActive($user)) throw new ApiException("El usuario no esta activo", 401);

        // Check active profiles (same as signIn)
        $profilesCount = $this->profileRepository->countByUserId($user->id);

        $profileId = null;
        $me = null;
        if ($profilesCount === 1) {
            $me = ($this->meQuery)($user, null);
            $profileId = $me['profile']['id'];
        }

        $token = JWTAuth::claims([
            'prf' => $profileId,
        ])->fromUser($user);

        // Create session
        $this->userRepository->updateLastSignIn($user);
        $this->sessionRepository->create(
            $user->id,
            $profileId,
            $request->ip(),
            $request->userAgent()
        );

        return (object)[
            'token' => $token,
            'user' => $me
        ];
    }
}
