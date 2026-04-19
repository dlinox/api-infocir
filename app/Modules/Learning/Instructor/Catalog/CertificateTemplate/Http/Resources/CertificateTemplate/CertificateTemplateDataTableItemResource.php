<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Http\Resources\CertificateTemplate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CertificateTemplateDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'pageSize'     => $this->page_size,
            'orientation'  => $this->orientation,
            'validityDays' => $this->validity_days,
            'isActive'     => $this->is_active,
            'updatedAt'    => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}
