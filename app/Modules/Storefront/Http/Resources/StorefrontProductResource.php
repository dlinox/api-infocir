<?php

namespace App\Modules\Storefront\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorefrontProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $plant = $this->plant;
        $productType = $product?->productType;

        return [
            'id'             => $this->id,
            'nombre'         => $product?->name ?? '',
            'slug'           => $product?->slug,
            'descripcion'    => $product?->description ?? '',
            'imagen'         => '',
            'categoria'      => $productType?->name ?? '',
            'categoriaIcon'  => $productType?->icon,
            'categoriaColor' => $productType?->color,
            'planta'         => $plant ? [
                'id'        => $plant->id,
                'nombre'    => $plant->name,
                'slug'      => $plant->slug,
                'ubicacion' => $plant->cityRelation
                    ? trim($plant->cityRelation->district . ', ' . $plant->cityRelation->department)
                    : '',
            ] : null,
            'presentaciones' => StorefrontPresentationResource::collection($this->presentations),
        ];
    }
}
