<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\AreaRepository;

class AreaService
{
    public function __construct(
        private AreaRepository $areaRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->areaRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->areaRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->areaRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->areaRepository->getSelectItems();
    }
}
