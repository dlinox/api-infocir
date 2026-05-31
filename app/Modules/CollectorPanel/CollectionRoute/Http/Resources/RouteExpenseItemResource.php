<?php

namespace App\Modules\CollectorPanel\CollectionRoute\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RouteExpenseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'value'       => $this->id,
            'title'       => $this->name,
            'color'       => $this->color,
            'unitMeasure' => $this->unitMeasure
                ? ['id' => $this->unitMeasure->id, 'abbreviation' => $this->unitMeasure->abbreviation]
                : null,
        ];
    }
}
