<?php

namespace App\Modules\Admin\Dairy\PlantWorker\Http\Resources\PlantWorker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlantWorkerDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'personId'       => $this->person_id,
            'fullName'       => $this->whenLoaded('person', fn() => $this->person->full_name),
            'documentType'   => $this->whenLoaded('person', fn() => $this->person->document_type),
            'documentNumber' => $this->whenLoaded('person', fn() => $this->person->document_number),
            'cellphone'      => $this->whenLoaded('person', fn() => $this->person->cellphone),
            'email'          => $this->whenLoaded('person', fn() => $this->person->email),
            'plantId'        => $this->plant_id,
            'plantName'      => $this->whenLoaded('plant', fn() => $this->plant->name),
            'positionId'     => $this->position_id,
            'positionName'   => $this->whenLoaded('position', fn() => $this->position?->name),
            'instructionDegreeId'   => $this->instruction_degree_id,
            'instructionDegreeName' => $this->whenLoaded('instructionDegree', fn() => $this->instructionDegree?->name),
            'professionId'   => $this->profession_id,
            'professionName' => $this->whenLoaded('profession', fn() => $this->profession?->name),
            'isManager'      => $this->is_manager,
            'isActive'       => $this->is_active,
        ];
    }
}
