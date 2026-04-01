<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\SupplyRepository;

class SupplyService
{
    public function __construct(
        private SupplyRepository $supplyRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->supplyRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->supplyRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->supplyRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->supplyRepository->getSelectItems();
    }
}
