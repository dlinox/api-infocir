<?php

namespace App\Modules\Learning\Learner\Support;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Profile as CoreProfile;
use App\Models\Dairy\Worker;
use App\Modules\Auth\Services\AuthService;

class LearnerContext
{
    private ?Worker $worker = null;

    public function __construct(
        private AuthService $authService,
    ) {}

    public function worker(): Worker
    {
        if ($this->worker !== null) {
            return $this->worker;
        }

        $profileId = $this->authService->getProfileIdFromToken();
        if (!$profileId) {
            throw new ApiException('No se encontró el perfil del usuario.', 401);
        }

        $behaviorProfile = BehaviorProfile::find($profileId);
        if (!$behaviorProfile) {
            throw new ApiException('Perfil de sesión inválido.', 401);
        }

        $coreProfile = CoreProfile::find($behaviorProfile->core_profile_id);
        if (!$coreProfile || $coreProfile->profileable_type !== 'dairy_workers') {
            throw new ApiException('Tu cuenta no está registrada como trabajador.', 403);
        }

        $worker = Worker::where('person_id', $coreProfile->profileable_id)->first();
        if (!$worker) {
            throw new ApiException('No se encontró tu ficha de trabajador.', 404);
        }

        return $this->worker = $worker;
    }

    public function workerId(): int
    {
        return $this->worker()->person_id;
    }
}
