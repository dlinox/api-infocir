<?php

namespace App\Modules\Shared\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name . ' ' . $this->paternal_surname . ' ' . $this->maternal_surname . ' (' . $this->document_number . ')',
        ];
    }
}
