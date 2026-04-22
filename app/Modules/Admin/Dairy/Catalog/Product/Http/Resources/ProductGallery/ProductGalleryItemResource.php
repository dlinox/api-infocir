<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Http\Resources\ProductGallery;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductGalleryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'productId'      => $this->product_id,
            'presentationId' => $this->presentation_id,
            'fileId'         => $this->file_id,
            'caption'        => $this->caption,
            'isActive'       => $this->is_active,
            'presentation'   => $this->presentation ? [
                'id'   => $this->presentation->id,
                'name' => $this->presentation->name,
                'sku'  => $this->presentation->sku,
            ] : null,
            'file'           => $this->file ? [
                'id'       => $this->file->id,
                'url'      => $this->file->url,
                'filename' => $this->file->filename,
                'mimeType' => $this->file->mime_type,
                'size'     => $this->file->size,
            ] : null,
        ];
    }
}
