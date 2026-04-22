<?php

namespace App\Modules\Admin\Dairy\Organization\Plant\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Organization\Plant\Repositories\PlantGalleryRepository;

class PlantGalleryService
{
    public function __construct(
        private PlantGalleryRepository $plantGalleryRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->plantGalleryRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->plantGalleryRepository->createOrUpdate($data);
    }

    public function delete(int $id): void
    {
        $this->plantGalleryRepository->delete($id);
    }
}
