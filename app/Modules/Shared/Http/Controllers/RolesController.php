<?php

namespace App\Modules\Shared\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Models\Behavior\Role;

class RolesController
{
    public function selectItems()
    {
        $scopesRaw = request()->query('scopes');
        $scopes    = $scopesRaw ? explode(',', $scopesRaw) : [];
        $level     = request()->query('level');

        $roles = Role::when(!empty($scopes), fn($q) => $q->whereIn('scope', $scopes))
            ->when(!is_null($level), fn($q) => $q->where('level', $level))
            ->where('is_active', true)
            ->orderBy('display_name')
            ->get(['id', 'display_name'])
            ->map(fn($r) => ['value' => $r->id, 'title' => $r->display_name]);

        return ApiResponse::success($roles);
    }
}
