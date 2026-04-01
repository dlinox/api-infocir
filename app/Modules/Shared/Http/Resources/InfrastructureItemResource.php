<?php

namespace App\Modules\Shared\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfrastructureItemResource extends JsonResource
{
    private const TYPE_LABELS = [
        'dairy_plants' => 'Planta lechera',
    ];

    public function toArray($request)
    {
        $type = self::TYPE_LABELS[$this->infrastructurable_type] ?? class_basename($this->infrastructurable_type);
        $name = $this->infrastructurable->name ?? 'Sin nombre';

        return [
            'id' => $this->id,
            'branchId' => $this->infrastructurable->id,
            'type' => $type,
            'name' =>  $name,
            'isActive' => $this->infrastructurable->is_active ?? false,
        ];
    }
}
