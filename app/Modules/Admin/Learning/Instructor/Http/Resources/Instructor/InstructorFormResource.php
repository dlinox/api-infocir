<?php

namespace App\Modules\Admin\Learning\Instructor\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorFormResource extends JsonResource
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
            ],
            'isActive' => $this->is_active,
        ];
    }
}
