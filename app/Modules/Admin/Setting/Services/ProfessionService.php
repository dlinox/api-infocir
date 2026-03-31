<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\ProfessionRepository;

class ProfessionService
{
    public function __construct(
        private ProfessionRepository $professionRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->professionRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->professionRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->professionRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->professionRepository->getSelectItems();
    }
}
