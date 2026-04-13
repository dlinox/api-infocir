<?php

namespace App\Modules\Shared\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfrastructureSelectItemResource extends JsonResource
{
    private const TYPE_LABELS = [
        'dairy_plants'    => 'Planta lechera',
        'dairy_suppliers' => 'Proveedor',
    ];

    private const TYPE_SCOPES = [
        'dairy_plants'    => 'plant',
        'dairy_suppliers' => 'supplier',
    ];

    public function toArray($request)
    {
        $type = self::TYPE_LABELS[$this->entityable_type] ?? class_basename($this->entityable_type);
        $name = $this->entityable->name ?? 'Sin nombre';

        return [
            'value' => $this->id,
            'title' => "({$type}) {$name}",
            'type'  => self::TYPE_SCOPES[$this->entityable_type] ?? null,
        ];
    }
}
