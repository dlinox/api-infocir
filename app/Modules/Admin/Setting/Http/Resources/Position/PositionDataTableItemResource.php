<?php

namespace App\Modules\Admin\Setting\Http\Resources\Position;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PositionDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'entityType'         => $this->entity_type,
            'role'               => $this->role ? ['id' => $this->role->id, 'name' => $this->role->display_name] : null,
            'investmentCategory' => $this->investmentCategory ? ['id' => $this->investmentCategory->id, 'name' => $this->investmentCategory->name] : null,
            'isActive'           => $this->is_active,
        ];
    }
}
