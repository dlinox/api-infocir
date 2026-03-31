<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\ProductTypeRepository;

class ProductTypeService
{
    public function __construct(
        private ProductTypeRepository $productTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->productTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->productTypeRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->productTypeRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->productTypeRepository->getSelectItems();
    }
}
