<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Setting\Repositories\DocumentTypeRepository;

class DocumentTypeService
{
    public function __construct(
        private DocumentTypeRepository $documentTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->documentTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->documentTypeRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        return $this->documentTypeRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->documentTypeRepository->getSelectItems();
    }
}
