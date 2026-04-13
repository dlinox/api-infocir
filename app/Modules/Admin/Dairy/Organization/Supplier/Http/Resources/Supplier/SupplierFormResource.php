<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'supplierType'   => $this->supplier_type,
            'documentType'   => $this->document_type,
            'documentNumber' => $this->document_number,
            'name'           => $this->name,
            'tradeName'      => $this->trade_name,
            'cellphone'      => $this->cellphone,
            'email'          => $this->email,
            'address'        => $this->address,
            'city'           => $this->city,
            'latitude'       => $this->latitude,
            'longitude'      => $this->longitude,
            'description'    => $this->description,
            'isActive'       => $this->is_active,
        ];
    }
}

