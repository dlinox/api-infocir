<?php

namespace App\Modules\Admin\Learning\Instructor\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorDataTableItemResource extends JsonResource
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
            'isActive' => $this->is_active,
        ];
    }
}
