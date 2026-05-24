<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\PreOperativeCatalogRepository;

class PreOperativeCatalogService
{
    public function __construct(
        private PreOperativeCatalogRepository $preOperativeCatalogRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->preOperativeCatalogRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->preOperativeCatalogRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->preOperativeCatalogRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->preOperativeCatalogRepository->getSelectItems();
    }
}
