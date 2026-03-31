<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\CompanyTypeRepository;

class CompanyTypeService
{
    public function __construct(
        private CompanyTypeRepository $companyTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->companyTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->companyTypeRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->companyTypeRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->companyTypeRepository->getSelectItems();
    }
}
