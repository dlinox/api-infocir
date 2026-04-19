<?php

namespace App\Modules\Learning\Instructor\Scheduling\Certification\Http\Resources\Certification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificationDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'certificateNumber' => $this->certificate_number,
            'worker'            => collect([$this->worker_name, $this->worker_paternal_surname, $this->worker_maternal_surname])->filter()->implode(' '),
            'template'          => $this->template_name,
            'issuedAt'          => $this->issued_at,
            'expiresAt'         => $this->expires_at,
        ];
    }
}
