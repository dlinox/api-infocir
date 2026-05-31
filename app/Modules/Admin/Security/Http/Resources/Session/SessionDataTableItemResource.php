<?php

namespace App\Modules\Admin\Security\Http\Resources\Session;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'ipAddress'  => $this->ip_address,
            'userAgent'  => $this->user_agent,
            'lastUsedAt' => $this->last_used_at,
            'expiresAt'  => $this->expires_at,
            'user'       => $this->user ? [
                'id'       => $this->user->id,
                'username' => $this->user->username,
            ] : null,
        ];
    }
}
