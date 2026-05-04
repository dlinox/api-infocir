<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\PreOperativeCatalogRepository;

class PreOperativeCatalogService
{
    public function __construct(
        private PreOperativeCatalogRepository $repository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->repository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->repository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->repository->getSelectItems();
    }
}
