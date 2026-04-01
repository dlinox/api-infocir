<?php

namespace App\Modules\Admin\Dairy\Supplier\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'personId'       => $this->person_id,
            'fullName'       => $this->whenLoaded('person', fn() => $this->person->full_name),
            'documentType'   => $this->whenLoaded('person', fn() => $this->person->document_type),
            'documentNumber' => $this->whenLoaded('person', fn() => $this->person->document_number),
            'supplierType'   => $this->supplier_type,
            'tradeName'      => $this->trade_name,
            'cellphone'      => $this->cellphone,
            'email'          => $this->email,
            'address'        => $this->address,
            'isActive'       => $this->is_active,
        ];
    }
}
