<?php

namespace App\Modules\Shared\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'documentType'    => $this->document_type,
            'documentNumber'  => $this->document_number,
            'name'            => $this->name,
            'paternalSurname' => $this->paternal_surname,
            'maternalSurname' => $this->maternal_surname,
            'dateBirth'       => $this->date_birth?->format('Y-m-d'),
            'cellphone'       => $this->cellphone,
            'email'           => $this->email,
            'gender'          => $this->gender,
            'address'         => $this->address,
            'city'            => $this->city,
            'country'         => $this->country,
        ];
    }
}
