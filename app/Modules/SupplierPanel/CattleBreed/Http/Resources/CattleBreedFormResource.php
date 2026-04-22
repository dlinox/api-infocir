<?php

namespace App\Modules\SupplierPanel\CattleBreed\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CattleBreedFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'breedName' => $this->breed_name,
            'count'     => $this->count,
            'notes'     => $this->notes,
            'isActive'  => (bool) $this->is_active,
        ];
    }
}
