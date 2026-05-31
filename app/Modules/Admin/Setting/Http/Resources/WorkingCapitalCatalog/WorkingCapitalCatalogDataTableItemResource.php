<?php

namespace App\Modules\Admin\Setting\Http\Resources\WorkingCapitalCatalog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkingCapitalCatalogDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'description'        => $this->description,
            'color'              => $this->color,
            'recurrenceType'     => $this->recurrence_type,
            'recurrenceEveryDays' => $this->recurrence_every_days,
            'isActive'           => $this->is_active,
            'investmentCategory' => $this->investmentCategory
                ? ['id' => $this->investmentCategory->id, 'name' => $this->investmentCategory->name]
                : null,
            'iconFile'           => $this->iconFile
                ? [
                    'id'       => $this->iconFile->id,
                    'url'      => $this->iconFile->url,
                    'filename' => $this->iconFile->filename,
                    'mimeType' => $this->iconFile->mime_type,
                    'size'     => $this->iconFile->size,
                    'caption'  => $this->iconFile->caption,
                ]
                : null,
        ];
    }
}
