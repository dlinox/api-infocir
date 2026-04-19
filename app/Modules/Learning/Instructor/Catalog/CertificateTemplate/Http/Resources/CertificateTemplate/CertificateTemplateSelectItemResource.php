<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Resources\CertificateTemplate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateTemplateSelectItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
