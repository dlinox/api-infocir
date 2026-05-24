<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Auth\Session;

class SessionRepository
{
    public function create(int $userId, ?int $profileId, ?string $ipAddress, ?string $userAgent, string $token): Session
    {
        return Session::create([
            'user_id'       => $userId,
            'profile_id'    => $profileId,
            'session_token' => $token,
            'ip_address'    => $ipAddress,
            'user_agent'    => $userAgent,
            'last_used_at'  => now(),
            'expires_at'    => now()->addDays(7),
        ]);
    }

    public function existsByToken(string $token): bool
    {
        return Session::where('session_token', $token)
            ->where('expires_at', '>', now())
            ->exists();
    }

    public function replaceToken(string $oldToken, string $newToken): void
    {
        Session::where('session_token', $oldToken)
            ->update([
                'session_token' => $newToken,
                'last_used_at'  => now(),
                'expires_at'    => now()->addDays(7),
            ]);
    }

    public function invalidateByToken(string $token): void
    {
        Session::where('session_token', $token)->delete();
    }

    public function invalidateAllForUser(int $userId): void
    {
        Session::where('user_id', $userId)->delete();
    }
}
