<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Auth\User;

class UserRepository
{
    /**
     * Find user by identifier (username or email)
     */
    public function findByIdentifier(string $identifier): ?User
    {
        return User::where('username', $identifier)
            ->orWhere('email', $identifier)
            ->first();
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find user by username
     */
    public function findByUsername(string $username): ?User
    {
        return User::where('username', $username)->first();
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Update user's last sign in timestamp
     */
    public function updateLastSignIn(User $user): void
    {
        $user->update(['last_sign_in_at' => now()]);
    }

    /**
     * Check if user is active
     */
    public function isActive(User $user): bool
    {
        return $user->is_active;
    }
}
