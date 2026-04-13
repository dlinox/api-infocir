<?php

namespace App\Modules\Auth\Repositories\Actions;

use Illuminate\Http\Request;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;

use App\Modules\Auth\Repositories\ProfileRepository;
use App\Modules\Auth\Repositories\SessionRepository;
use App\Modules\Auth\Repositories\UserRepository;
use App\Modules\Auth\Repositories\Queries\MeQuery;

class SignInAction
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionRepository $sessionRepository,
        private ProfileRepository $profileRepository,
        private MeQuery $meQuery
    ) {}

    public function execute(User $user, string $app, Request $request): object
    {
        $profilesCount = $this->profileRepository->countByUserId($user->id);

        if ($profilesCount === 0) {
            throw new ApiException("No tienes perfiles asignados", 403);
        }

        $allowedLevels = config("app.modules.{$app}.levels", []);
        $allowedProfiles = $this->profileRepository->getProfilesWithLevels($user->id, $allowedLevels);

        if ($allowedProfiles->isEmpty()) {
            throw new ApiException("No tienes acceso a este módulo", 403);
        }

        $profileId = null;
        $me = null;
        if ($allowedProfiles->count() === 1) {
            $me = ($this->meQuery)($user, $allowedProfiles->first()->id);
            $profileId = $me['profile']['id'];
        }

        $token = JWTAuth::claims([
            'prf' => $profileId,
            'app' => $app,
        ])->fromUser($user);

        $this->userRepository->updateLastSignIn($user);
        $this->sessionRepository->create(
            $user->id,
            $profileId,
            $request->ip(),
            $request->userAgent()
        );

        return (object)[
            'token' => $token,
            'user'  => $me,
        ];
    }
}
