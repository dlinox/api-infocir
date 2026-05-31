<?php

namespace App\Modules\Admin\Security\Http\Resources\Permission;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->display_name,
            'value' => $this->id,
            'type'  => $this->type,
        ];
    }
}
