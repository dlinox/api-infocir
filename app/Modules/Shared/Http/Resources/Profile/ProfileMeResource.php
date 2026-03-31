<?php

namespace App\Modules\Shared\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileMeResource extends JsonResource
{
    private $person;
    private $profile;

    public function __construct($user, $person, $profile)
    {
        parent::__construct($user);
        $this->person  = $person;
        $this->profile = $profile;
    }

    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id'       => $this->id,
                'username' => $this->username,
                'email'    => $this->email,
            ],
            'person' => [
                'id'               => $this->person->id,
                'documentType'     => $this->person->document_type,
                'documentNumber'   => $this->person->document_number,
                'name'             => $this->person->name,
                'paternalSurname'  => $this->person->paternal_surname,
                'maternalSurname'  => $this->person->maternal_surname,
                'dateBirth'        => $this->person->date_birth?->format('Y-m-d'),
                'phone'            => $this->person->phone,
                'email'            => $this->person->email,
                'gender'           => $this->person->gender,
                'address'          => $this->person->address,
            ],
        ];
    }
}
