<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\WorkingCapitalCatalogRepository;

class WorkingCapitalCatalogService
{
    public function __construct(
        private WorkingCapitalCatalogRepository $workingCapitalCatalogRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->workingCapitalCatalogRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->workingCapitalCatalogRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->workingCapitalCatalogRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->workingCapitalCatalogRepository->getSelectItems();
    }
}
