<?php

namespace App\Modules\Storefront\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StorefrontPresentationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $content = (float) $this->content;
        $contentLabel = rtrim(rtrim(number_format($content, 3, '.', ''), '0'), '.');
        $abbreviation = $this->unitMeasure?->abbreviation;

        return [
            'id'        => $this->id,
            'nombre'    => $this->name,
            'unidad'    => $this->unitMeasure?->name ?? '',
            'contenido' => trim($contentLabel . ' ' . ($abbreviation ?? '')),
            'precio'    => $this->currentPrice(),
            'stock'     => $this->available_stock !== null ? (int) $this->available_stock : null,
        ];
    }

    private function currentPrice(): float
    {
        $today = now()->toDateString();

        $price = $this->prices
            ->filter(function ($price) use ($today) {
                $from = $price->effective_from?->toDateString();
                $until = $price->effective_until?->toDateString();
                return (!$from || $from <= $today) && (!$until || $until >= $today);
            })
            ->sortByDesc('effective_from')
            ->first();

        return (float) ($price->price ?? 0);
    }
}
