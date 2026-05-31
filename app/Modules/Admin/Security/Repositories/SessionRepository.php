<?php

namespace App\Modules\Admin\Security\Repositories;

use App\Models\Auth\Session;

class SessionRepository
{
    public function dataTable($request)
    {
        $query = Session::query()->with('user:id,username');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderByDesc('last_used_at');
        }

        return $query->dataTable($request);
    }

    public function getForUser(int $userId)
    {
        return Session::where('user_id', $userId)
            ->orderByDesc('last_used_at')
            ->get();
    }

    public function revoke(int $id): void
    {
        Session::where('id', $id)->delete();
    }

    public function revokeAllForUser(int $userId): void
    {
        Session::where('user_id', $userId)->delete();
    }
}
