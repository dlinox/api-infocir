<?php

namespace App\Modules\Admin\Security\Http\Resources\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'displayName' => $this->display_name,
            'type'        => $this->type,
            'level'       => $this->level,
            'parent'      => $this->parent ? [
                'id'          => $this->parent->id,
                'displayName' => $this->parent->display_name,
            ] : null,
        ];
    }
}
