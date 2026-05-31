<?php

namespace App\Modules\Admin\Security\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'displayName'   => $this->display_name,
            'level'         => $this->level,
            'scope'         => $this->scope,
            'isActive'      => $this->is_active,
            'redirectTo'    => $this->redirect_to,
            'isSystem'      => in_array($this->name, ['super_admin', 'admin'], true),
            'permissionIds' => $this->permissions->pluck('id'),
        ];
    }
}
