<?php

namespace App\Modules\Shared\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Models\Behavior\Role;

class RolesController
{
    public function selectItems()
    {
        $scope = request()->query('scope');

        $roles = Role::when($scope, fn($q) => $q->where('scope', $scope))
            ->where('is_active', true)
            ->orderBy('display_name')
            ->get(['id', 'display_name'])
            ->map(fn($r) => ['value' => $r->id, 'title' => $r->display_name]);

        return ApiResponse::success($roles);
    }
}
