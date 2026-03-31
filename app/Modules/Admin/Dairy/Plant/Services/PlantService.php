<?php

namespace App\Modules\Admin\Dairy\Plant\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Plant\Repositories\PlantRepository;

class PlantService
{
    public function __construct(
        private PlantRepository $plantRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->plantRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->plantRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->plantRepository->delete($id);
    }
}
