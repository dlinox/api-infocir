<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\UnitMeasureRepository;

class UnitMeasureService
{
    public function __construct(
        private UnitMeasureRepository $unitMeasureRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->unitMeasureRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->unitMeasureRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->unitMeasureRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->unitMeasureRepository->getSelectItems();
    }
}
