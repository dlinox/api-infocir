<?php

namespace App\Modules\Admin\Dairy\Catalog\Product\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Dairy\Catalog\Product\Repositories\ProductRepository;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->productRepository->dataTable($request);
    }

    public function findById(string $id)
    {
        return $this->productRepository->findById($id);
    }

    public function save(array $data)
    {
        return $this->productRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->productRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->productRepository->getSelectItems();
    }
}
