<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Http\Resources\SupplierGallery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierGalleryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'supplierId' => $this->supplier_id,
            'fileId'     => $this->file_id,
            'caption'    => $this->caption,
            'isActive'   => $this->is_active,
            'file'       => $this->file ? [
                'id'       => $this->file->id,
                'url'      => $this->file->url,
                'filename' => $this->file->filename,
                'mimeType' => $this->file->mime_type,
                'size'     => $this->file->size,
            ] : null,
        ];
    }
}
