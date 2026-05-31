<?php

namespace App\Modules\Storefront\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorefrontPlantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $capacity = $this->capacity_liters !== null ? (float) $this->capacity_liters : null;
        $city = $this->cityRelation;
        $ubicacion = $city ? trim($city->district . ', ' . $city->department) : '';

        return [
            'id'              => $this->id,
            'nombre'          => $this->name,
            'slug'            => $this->slug,
            'descripcion'     => $this->description ?? '',
            'ubicacion'       => $ubicacion,
            'distrito'        => $city?->district ?? '',
            'imagen'          => '',
            'capacidadLitros' => $capacity,
            'tipo'            => $this->type === 'A' ? 'industrial' : 'artesanal',
            'coordenadas'     => ($this->longitude !== null && $this->latitude !== null)
                ? [(float) $this->longitude, (float) $this->latitude]
                : null,
            'telefono'        => $this->cellphone,
            'email'           => $this->email,
            'certificaciones' => $this->certificaciones(),
            'especialidad'    => $this->especialidad(),
            'productosDetalle' => $this->whenLoaded('plantProducts', fn () => $this->productosDetalle()),
        ];
    }

    private function certificaciones(): array
    {
        $items = [];
        if ($this->has_sanitary_registration) $items[] = 'Registro Sanitario';
        if ($this->has_digesa_parameters)     $items[] = 'DIGESA';
        if ($this->has_technification)         $items[] = 'Planta Tecnificada';
        if ($this->has_tdd_training)           $items[] = 'Capacitación TDD';
        return $items;
    }

    private function especialidad(): array
    {
        if (!$this->relationLoaded('plantProducts')) {
            return [];
        }

        return $this->plantProducts
            ->map(fn ($plantProduct) => $plantProduct->product?->productType?->name)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function productosDetalle(): array
    {
        return $this->plantProducts
            ->map(function ($plantProduct) {
                $product = $plantProduct->product;
                if (!$product) {
                    return null;
                }
                return [
                    'nombre'      => $product->name,
                    'descripcion' => $product->description ?? '',
                    'tag'         => $product->productType?->name,
                    'emoji'       => $product->productType?->icon,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }
}
