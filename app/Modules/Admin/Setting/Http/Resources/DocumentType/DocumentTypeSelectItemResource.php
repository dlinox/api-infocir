<?php

namespace App\Modules\Admin\Setting\Http\Resources\DocumentType;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->code,
        ];
    }
}
