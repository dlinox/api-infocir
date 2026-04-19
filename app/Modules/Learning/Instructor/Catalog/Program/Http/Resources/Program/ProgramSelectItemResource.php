<?php

namespace App\Modules\Learning\Instructor\Catalog\Program\Http\Resources\Program;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramSelectItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
