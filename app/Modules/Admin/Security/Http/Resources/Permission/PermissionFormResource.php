<?php

namespace App\Modules\Admin\Security\Http\Resources\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'displayName' => $this->display_name,
            'type'        => $this->type,
            'parentId'    => $this->parent_id,
            'level'       => $this->level,
        ];
    }
}
