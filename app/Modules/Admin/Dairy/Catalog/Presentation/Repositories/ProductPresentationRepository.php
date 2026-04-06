<?php

namespace App\Modules\Admin\Dairy\Catalog\Presentation\Repositories;

use App\Models\Dairy\ProductPresentation;
use Illuminate\Support\Facades\DB;

class ProductPresentationRepository
{
    public function dataTable($request)
    {
        $query = ProductPresentation::query()
            ->with(['unitMeasure', 'plantProduct.product']);

        if (!empty($request->filters['plant_id'])) {
            $query->whereHas('plantProduct', fn ($q) => $q->where('plant_id', $request->filters['plant_id']));
        }

        if (!empty($request->filters['plant_product_id'])) {
            $query->where('plant_product_id', $request->filters['plant_product_id']);
        }

        if (isset($request->filters['is_active']) && $request->filters['is_active'] !== null) {
            $query->where('is_active', $request->filters['is_active']);
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(string $id)
    {
        return ProductPresentation::with(['unitMeasure'])->findOrFail($id);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id'])) {
            $presentation = ProductPresentation::findOrFail($data['id']);
            unset($data['sku'], $data['barcode']);
            $presentation->update($data);
            return $presentation;
        }

        unset($data['sku'], $data['barcode']);

        return DB::transaction(function () use ($data) {
            $data['sku'] = 'TMP-' . uniqid();
            $presentation = ProductPresentation::create($data);

            $presentation->update([
                'sku' => 'SKU-' . str_pad($presentation->id, 6, '0', STR_PAD_LEFT),
                'barcode' => '200' . str_pad($presentation->id, 10, '0', STR_PAD_LEFT),
            ]);

            return $presentation->fresh();
        });
    }

    public function delete(string $id)
    {
        $presentation = ProductPresentation::findOrFail($id);
        $presentation->delete();
        return $presentation;
    }

    public function getSelectItems(int $plantProductId)
    {
        return ProductPresentation::where('plant_product_id', $plantProductId)
            ->where('is_active', true)
            ->with('unitMeasure')
            ->orderBy('name')
            ->get();
    }
}
