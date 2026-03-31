<?php

namespace App\Modules\Auth\Repositories\Queries;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Models\Auth\User;
use App\Common\Exceptions\ApiException;

use App\Modules\Auth\Repositories\ProfileRepository;

class MeQuery
{

    public function __construct(
        private ProfileRepository $profileRepository,
    ) {}

    public function __invoke(User $user, ?int $profileId): array
    {

        if ($profileId) {
            $profile = $this->profileRepository->findById($profileId);
        } else {
            $profile = $this->profileRepository->firstActiveByUserId($user->id);
        }

        if (!$profile) {
            JWTAuth::invalidate(JWTAuth::getToken());
            throw new ApiException("Perfil no encontrado", 404);
        }

        $profile->load('coreProfile.person', 'role.permissions');

        $person = $profile->coreProfile?->person;

        $fullName = $person
            ? collect([$person->name, $person->paternal_surname, $person->maternal_surname])->filter()->implode(' ')
            : $user->username;

        return [
            'name' => $fullName,
            'username' => $user->username,
            'email' => $user->email,
            'profile' => [
                'id' => $profile->id,
                'role' => $profile->role->display_name,
                'redirectTo' => $profile->role->redirect_to,
                'roleLevel' => $profile->role->level,
                'permissions' => $profile->role->permissions->pluck('name')->toArray(),
            ],
        ];
    }
}
