<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Resources\Supplier;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $person = $this->person;

        return [
            'person' => [
                'id'              => $person->id,
                'documentType'    => $person->document_type,
                'documentNumber'  => $person->document_number,
                'name'            => $person->name,
                'paternalSurname' => $person->paternal_surname,
                'maternalSurname' => $person->maternal_surname,
                'dateBirth'       => $person->date_birth?->format('Y-m-d'),
                'cellphone'       => $person->cellphone,
                'email'           => $person->email,
                'gender'          => $person->gender,
                'address'         => $person->address,
                'city'            => $person->city,
                'country'         => $person->country,
            ],
            'supplierType' => $this->supplier_type,
            'tradeName'    => $this->trade_name,
            'cellphone'    => $this->cellphone,
            'email'        => $this->email,
            'address'      => $this->address,
            'country'      => $this->country,
            'city'         => $this->city,
            'latitude'     => $this->latitude,
            'longitude'    => $this->longitude,
            'description'  => $this->description,
            'isActive'     => $this->is_active,
        ];
    }
}
