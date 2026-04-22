<?php

namespace App\Modules\Admin\Dairy\Organization\Supplier\Repositories;

use App\Models\Dairy\SupplierGallery;

class SupplierGalleryRepository
{
    public function dataTable($request)
    {
        $query = SupplierGallery::query()
            ->with('file');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data): SupplierGallery
    {
        if (isset($data['id'])) {
            $gallery = SupplierGallery::findOrFail($data['id']);
            $gallery->update($data);
            return $gallery->load('file');
        }

        return SupplierGallery::create($data)->load('file');
    }

    public function delete(int $id): void
    {
        SupplierGallery::findOrFail($id)->delete();
    }
}
