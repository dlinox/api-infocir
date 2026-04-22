<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\InvestmentCategoryRepository;

class InvestmentCategoryService
{
    public function __construct(
        private InvestmentCategoryRepository $investmentCategoryRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->investmentCategoryRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->investmentCategoryRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->investmentCategoryRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->investmentCategoryRepository->getSelectItems();
    }
}
