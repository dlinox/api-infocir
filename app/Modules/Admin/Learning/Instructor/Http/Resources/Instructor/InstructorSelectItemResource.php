<?php

namespace App\Modules\Admin\Learning\Instructor\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstructorSelectItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => collect([$this->person_name, $this->person_paternal_surname, $this->person_maternal_surname])->filter()->implode(' '),
            'value' => $this->id,
        ];
    }
}
