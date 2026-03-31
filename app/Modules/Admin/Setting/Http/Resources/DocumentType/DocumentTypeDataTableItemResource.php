<?php

namespace App\Modules\Admin\Setting\Http\Resources\DocumentType;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code'     => $this->code,
            'name'     => $this->name,
            'isActive' => $this->is_active,
        ];
    }
}
