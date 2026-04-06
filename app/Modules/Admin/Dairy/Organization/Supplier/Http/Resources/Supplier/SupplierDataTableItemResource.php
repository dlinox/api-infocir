<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'personId' => $this->person_id,
            'person'   => [
                'fullName'       => collect([$this->person_name, $this->person_paternal_surname, $this->person_maternal_surname])->filter()->implode(' '),
                'documentType'   => $this->person_document_type,
                'documentNumber' => $this->person_document_number,
                'cellphone'      => $this->person_cellphone,
                'email'          => $this->person_email,
            ],
            'supplierType' => $this->supplier_type,
            'tradeName'    => $this->trade_name,
            'cellphone'    => $this->cellphone,
            'email'        => $this->email,
            'address'      => $this->address,
            'isActive'     => $this->is_active,
        ];
    }
}
