<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Http\Resources\ProductPresentation;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductPresentationSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $content = $this->content;
        $unit = $this->unitMeasure?->name ?? '';
        $label = "{$this->sku} — {$this->name}";
        if ($content) {
            $label .= " ({$content} {$unit})";
        }

        return [
            'title' => trim($label),
            'value' => $this->id,
        ];
    }
}
