<?php

namespace App\Modules\Auth\Repositories;

use Illuminate\Support\Collection;
use App\Models\Behavior\BehaviorProfile;

class ProfileRepository
{
    public function getByUserId(int $userId): Collection
    {
        return BehaviorProfile::select(
            'behavior_profiles.id',
            'behavior_roles.display_name as role_name'
        )
            ->join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.role_id')
            ->where('behavior_profiles.user_id', $userId)
            ->where('behavior_profiles.is_active', true)
            ->get();
    }

    public function findProfile(int $userId, int $coreProfileId): ?BehaviorProfile
    {
        return BehaviorProfile::where('user_id', $userId)
            ->where('core_profile_id', $coreProfileId)
            ->first();
    }

    public function countByUserId(int $userId): int
    {
        return BehaviorProfile::where('user_id', $userId)
            ->where('is_active', true)
            ->count();
    }

    public function firstActiveByUserId(int $userId): ?BehaviorProfile
    {
        return BehaviorProfile::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }

    public function findById(int $profileId): ?BehaviorProfile
    {
        return BehaviorProfile::where('id', $profileId)
            ->where('is_active', true)
            ->first();
    }

    public function hasProfileWithLevels(int $userId, array $levels): bool
    {
        // Convertir a strings porque en BD el level es varchar
        $levels = array_map('strval', $levels);

        return BehaviorProfile::join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.role_id')
            ->where('behavior_profiles.user_id', $userId)
            ->where('behavior_profiles.is_active', true)
            ->whereIn('behavior_roles.level', $levels)
            ->exists();
    }

    public function getProfilesWithLevels(int $userId, array $levels): Collection
    {
        // Convertir a strings porque en BD el level es varchar
        $levels = array_map('strval', $levels);

        return BehaviorProfile::join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.role_id')
            ->where('behavior_profiles.user_id', $userId)
            ->where('behavior_profiles.is_active', true)
            ->whereIn('behavior_roles.level', $levels)
            ->select('behavior_profiles.*')
            ->get();
    }

    public function getByUserIdAndLevels(int $userId, array $levels): Collection
    {
        // Convertir a strings porque en BD el level es varchar
        $levels = array_map('strval', $levels);

        return BehaviorProfile::select(
            'behavior_profiles.id',
            'behavior_roles.display_name as role_name'
        )
            ->join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.role_id')
            ->where('behavior_profiles.user_id', $userId)
            ->where('behavior_profiles.is_active', true)
            ->whereIn('behavior_roles.level', $levels)
            ->get();
    }
}
