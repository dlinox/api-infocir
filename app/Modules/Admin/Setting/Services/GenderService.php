<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;

use App\Modules\Admin\Setting\Repositories\GenderRepository;

class GenderService
{
    public function __construct(
        private GenderRepository $genderRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->genderRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->genderRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->genderRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->genderRepository->getSelectItems();
    }
}
