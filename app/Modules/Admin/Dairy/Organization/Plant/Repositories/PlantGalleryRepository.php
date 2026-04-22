<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Repositories;

use App\Models\Dairy\PlantGallery;

class PlantGalleryRepository
{
    public function dataTable($request)
    {
        $query = PlantGallery::query()
            ->with('file');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data): PlantGallery
    {
        if (isset($data['id'])) {
            $gallery = PlantGallery::findOrFail($data['id']);
            $gallery->update($data);
            return $gallery->load('file');
        }

        return PlantGallery::create($data)->load('file');
    }

    public function delete(int $id): void
    {
        PlantGallery::findOrFail($id)->delete();
    }
}
