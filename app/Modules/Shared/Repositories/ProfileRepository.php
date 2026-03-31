<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Behavior\BehaviorProfile;

class ProfileRepository
{
    public function create(int $userId, int $roleId, int $coreProfileId): BehaviorProfile
    {
        return BehaviorProfile::create([
            'user_id' => $userId,
            'role_id' => $roleId,
            'core_profile_id' => $coreProfileId,
        ]);
    }

    public function findByCoreProfileId(int $coreProfileId): ?BehaviorProfile
    {
        return BehaviorProfile::where('core_profile_id', $coreProfileId)->first();
    }

    public function findByUserIdAndRoleId(int $userId, int $roleId): ?BehaviorProfile
    {
        return BehaviorProfile::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->first();
    }
}
