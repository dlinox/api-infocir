<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\AssetCatalogRepository;

class AssetCatalogService
{
    public function __construct(
        private AssetCatalogRepository $assetCatalogRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->assetCatalogRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->assetCatalogRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->assetCatalogRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->assetCatalogRepository->getSelectItems();
    }
}
