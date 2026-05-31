<?php

namespace App\Modules\Storefront\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorefrontCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'     => $this->id,
            'nombre' => $this->name,
            'icon'   => $this->icon,
            'color'  => $this->color,
        ];
    }
}
