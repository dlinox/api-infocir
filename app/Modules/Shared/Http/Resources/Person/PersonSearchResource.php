<?php

namespace App\Modules\Shared\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonSearchResource extends JsonResource
{
    public function toArray($request)
    {
        $person = $this->resource['person'];
        $user = $this->resource['user'] ?? null;

        return [
            'person' => [
                'id' => $person->id,
                'documentType' => $person->document_type,
                'documentNumber' => $person->document_number,
                'name' => $person->name,
                'paternalSurname' => $person->paternal_surname,
                'maternalSurname' => $person->maternal_surname,
                'email' => $person->email,
                'cellphone' => $person->cellphone,
                'dateBirth' => $person->date_birth?->format('Y-m-d'),
                'gender' => $person->gender,
                'address' => $person->address,
            ],
            'profiles' => $this->resource['profiles'],
            'user' => $user ? [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'isActive' => (bool) $user->is_active,
            ] : null,
        ];
    }
}
