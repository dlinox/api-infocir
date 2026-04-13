<?php

namespace App\Modules\Learning\Instructor\Catalog\Course\Http\Resources\Course;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSelectItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->name,
            'value' => $this->id,
        ];
    }
}
