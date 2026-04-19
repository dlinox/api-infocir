<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Resources\CertificateTemplate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateTemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'fields'       => $this->fields,
            'validityDays' => $this->validity_days,
            'isActive'     => $this->is_active,
            'background'   => $this->backgroundFile ? [
                'id'  => $this->backgroundFile->id,
                'url' => $this->backgroundFile->url,
            ] : null,
            'updatedAt'    => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
