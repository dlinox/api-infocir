<?php

namespace App\Modules\Admin\Security\Http\Resources\Role;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->display_name,
            'value' => $this->id,
            'scope' => $this->scope,
            'level' => $this->level,
        ];
    }
}
