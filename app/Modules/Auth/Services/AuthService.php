<?php

namespace App\Modules\Auth\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Common\Exceptions\ApiException;

use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Entity;
use App\Models\Core\Profile as CoreProfile;
use App\Models\Dairy\Worker;

use App\Modules\Auth\Repositories\Actions\SignInAction;
use App\Modules\Auth\Repositories\ProfileRepository;
use App\Modules\Auth\Repositories\Queries\MeQuery;
use App\Modules\Auth\Repositories\SessionRepository;
use App\Modules\Auth\Repositories\UserRepository;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionRepository $sessionRepository,
        private ProfileRepository $profileRepository,
        private MeQuery $meQuery,
        private SignInAction $signInAction
    ) {}

    public function signIn(string $identifier, string $password, Request $request): ?object
    {
        $user = $this->userRepository->findByIdentifier($identifier);

        if (!$user) throw new ApiException("El usuario no existe", 401);
        if (!$this->userRepository->isActive($user)) throw new ApiException("El usuario no esta activo", 401);
        if (!Hash::check($password, $user->password)) throw new ApiException("Credenciales invalidas", 401);

        $app = $this->inferAppFromRequest($request);

        return $this->signInAction->execute($user, $app, $request);
    }

    /**
     * Get active profiles for user (filtered by app)
     */
    public function getProfiles(): Collection
    {
        $user = JWTAuth::user();
        $payload = JWTAuth::parseToken()->getPayload();
        $app = $payload->get('app') ?? 'core';

        $allowedLevels = config("app.modules.{$app}.levels", []);
        $profiles = $this->profileRepository->getByUserIdAndLevels($user->id, $allowedLevels);

        return $profiles;
    }

    public function selectProfile(int $profileId): object
    {
        $profile = $this->profileRepository->findById($profileId);

        if (!$profile) throw new ApiException("Perfil no encontrado", 404);

        $user = JWTAuth::user();

        $oldPayload = JWTAuth::parseToken()->getPayload();
        $app = $oldPayload->get('app') ?? 'core';

        $this->validateRoleLevelForApp($profile->role->level, $app);

        $me = ($this->meQuery)($user, $profileId);

        JWTAuth::invalidate(JWTAuth::getToken());

        $token = JWTAuth::claims([
            'prf' => $profileId,
            'app' => $app,
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
     * Get the entity (plant or supplier) assigned to the current worker profile
     */
    public function getMyEntity(): array
    {
        $profileId = $this->getProfileIdFromToken();
        if (!$profileId) throw new ApiException("No se encontró el perfil", 401);

        $behaviorProfile = BehaviorProfile::find($profileId);
        if (!$behaviorProfile) throw new ApiException("Perfil no encontrado", 404);

        $coreProfile = CoreProfile::find($behaviorProfile->core_profile_id);
        if (!$coreProfile) throw new ApiException("Perfil core no encontrado", 404);

        $worker = Worker::where('person_id', $coreProfile->profileable_id)->first();
        if (!$worker) throw new ApiException("No tiene trabajador asignado", 404);

        $entity = Entity::find($worker->entity_id);
        if (!$entity) throw new ApiException("No tiene entidad asignada", 404);

        $entityable = $entity->entityable;
        if (!$entityable) throw new ApiException("Entidad no encontrada", 404);

        $type = match ($entity->entityable_type) {
            'dairy_plants'    => 'plant',
            'dairy_suppliers' => 'supplier',
            default           => 'unknown',
        };

        $name = $entity->entityable_type === 'dairy_suppliers'
            ? ($entityable->trade_name ?? $entityable->name)
            : $entityable->name;

        return [
            'type' => $type,
            'id'   => $entityable->id,
            'name' => $name,
        ];
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

    public function signInWithGoogle(string $email, Request $request): ?object
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) throw new ApiException("No existe cuenta con este email", 401);
        if (!$this->userRepository->isActive($user)) throw new ApiException("El usuario no esta activo", 401);

        $app = $this->inferAppFromRequest($request);

        return $this->signInAction->execute($user, $app, $request);
    }

    private function inferAppFromRequest(Request $request): string
    {
        $origin = $request->header('Origin') ?? $request->header('Referer');

        if (!$origin) {
            throw new ApiException("Origen de la solicitud no detectado", 403);
        }

        foreach (config('app.modules') as $app => $module) {
            foreach ($module['origins'] as $allowedOrigin) {
                if (str_contains($origin, $allowedOrigin)) {
                    return $app;
                }
            }
        }

        throw new ApiException("Origen no autorizado", 403);
    }

    private function validateRoleLevelForApp(int $roleLevel, string $app): void
    {
        $levels = config("app.modules.{$app}.levels");

        if (!$levels) {
            throw new ApiException("Módulo de aplicación inválido", 400);
        }

        // Convertir a strings para comparar (level en BD es varchar)
        $levelsAsStrings = array_map('strval', $levels);

        if (!in_array((string)$roleLevel, $levelsAsStrings)) {
            throw new ApiException("No tienes acceso a este módulo", 401);
        }
    }
}
