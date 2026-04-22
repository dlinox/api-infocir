<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Repositories;

use App\Models\Dairy\ProductGallery;
use App\Models\Dairy\ProductPresentation;

class ProductGalleryRepository
{
    public function dataTable($request)
    {
        $query = ProductGallery::query()->with('file', 'presentation');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data): ProductGallery
    {
        if (isset($data['id'])) {
            $gallery = ProductGallery::findOrFail($data['id']);
            $gallery->update($data);
            return $gallery->load('file', 'presentation');
        }

        return ProductGallery::create($data)->load('file', 'presentation');
    }

    public function delete(int $id): void
    {
        ProductGallery::findOrFail($id)->delete();
    }

    public function getPresentationsByProduct(int $productId): array
    {
        return ProductPresentation::query()
            ->whereHas('plantProduct', fn($q) => $q->where('product_id', $productId))
            ->select('dairy_product_presentations.id', 'dairy_product_presentations.name', 'dairy_product_presentations.sku')
            ->get()
            ->map(fn($p) => ['id' => $p->id, 'name' => "[{$p->sku}] {$p->name}"])
            ->toArray();
    }
}
