<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\PositionRepository;

class PositionService
{
    public function __construct(
        private PositionRepository $positionRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->positionRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->positionRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->positionRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->positionRepository->getSelectItems();
    }
}
