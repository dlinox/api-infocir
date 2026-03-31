<?php

namespace App\Modules\Shared\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfrastructureSelectItemResource extends JsonResource
{
    private const TYPE_LABELS = [
        'academy_branches' => 'Académica',
        'barbershop_branches' => 'Barbería',
    ];

    public function toArray($request)
    {
        $type = self::TYPE_LABELS[$this->infrastructurable_type] ?? class_basename($this->infrastructurable_type);
        $name = $this->infrastructurable->name ?? 'Sin nombre';

        return [
            'value' => $this->id,
            'title' => "({$type}) {$name}",
        ];
    }
}
