<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\InstitutionTypeRepository;

class InstitutionTypeService
{
    public function __construct(
        private InstitutionTypeRepository $institutionTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->institutionTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->institutionTypeRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->institutionTypeRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->institutionTypeRepository->getSelectItems();
    }
}
