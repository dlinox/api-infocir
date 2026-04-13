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
        $type = self::TYPE_LABELS[$this->entityable_type] ?? class_basename($this->entityable_type);
        $name = $this->entityable->name ?? 'Sin nombre';

        return [
            'id' => $this->id,
            'branchId' => $this->entityable->id,
            'type' => $type,
            'name' =>  $name,
            'isActive' => $this->entityable->is_active ?? false,
        ];
    }
}
