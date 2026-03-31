<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Auth\Session;
use Illuminate\Support\Str;

class SessionRepository
{
    public function create(int $userId, ?int $profileId, ?string $ipAddress, ?string $userAgent): Session
    {
        return Session::create([
            'user_id' => $userId,
            'profile_id' => $profileId,
            'session_token' => Str::random(64),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_used_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function updateProfile(int $userId, int $profileId): void
    {
        Session::where('user_id', $userId)
            ->update(['profile_id' => $profileId]);
    }

    public function invalidateAllForUser(int $userId): void
    {
        Session::where('user_id', $userId)->delete();
    }
}
