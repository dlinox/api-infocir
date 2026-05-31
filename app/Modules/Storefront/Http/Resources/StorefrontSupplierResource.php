<?php

namespace App\Modules\Storefront\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class StorefrontSupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $tipo = $this->supplier_type === 'company' ? 'cooperativa' : 'individual';
        $ubicacion = $this->community ?: ($this->city ?? '');

        return [
            'id'              => $this->id,
            'nombre'          => $this->trade_name ?: $this->name,
            'slug'            => Str::slug($this->trade_name ?: $this->name),
            'descripcion'     => $this->description ?? '',
            'ubicacion'       => $ubicacion,
            'distrito'        => $this->community ?? '',
            'capacidadLitros' => $this->tank_capacity_liters !== null ? (float) $this->tank_capacity_liters : 0,
            'tipo'            => $tipo,
            'especialidad'    => 'Leche fresca',
            'coordenadas'     => ($this->latitude !== null && $this->longitude !== null)
                ? [(float) $this->longitude, (float) $this->latitude]
                : null,
            'telefono'        => $this->cellphone,
            'email'           => $this->email,
            'certificaciones' => [],
            'sociosGanaderos' => $this->total_cows ? (int) $this->total_cows : null,
        ];
    }
}
