<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Http\Resources\Worker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkerFormResource extends JsonResource
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
            'plantId'    => $this->plant_id,
            'positionId' => $this->position_id,
            'instructionDegreeId' => $this->instruction_degree_id,
            'professionId' => $this->profession_id,
            'isManager'  => $this->is_manager,
            'isActive'   => $this->is_active,
        ];
    }
}
