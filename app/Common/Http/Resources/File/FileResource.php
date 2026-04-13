<?php

namespace App\Common\Http\Resources\File;

use App\Common\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'url'      => $this->url,
            'filename' => $this->filename,
            'caption'  =>  $this->caption,
            'mimeType' => $this->mime_type,
            'size'     => $this->size,
        ];
    }
}
