<?php

namespace App\Modules\Auth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeResource extends JsonResource
{
    private $profile;

    public function __construct($user, $profile)
    {
        parent::__construct($user);
        $this->profile = $profile;
    }

    public function toArray(Request $request): array
    {
        $person = $this->profile?->profileable?->person;
        $role = $this->profile?->role;
        $permissions = $role?->permissions->pluck('name')->toArray() ?? [];

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'name' => $person?->full_name ?? $this->username,
            'role' => $role?->display_name ?? null,
            'permissions' => $permissions,
            'isActive' => $this->is_active,
        ];
    }
}
