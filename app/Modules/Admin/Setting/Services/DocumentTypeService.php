<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Common\Exceptions\ApiException;
use App\Models\Core\DocumentType;
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
        unset($data['is_system']);

        $existing = DocumentType::find($data['code']);
        if ($existing?->is_system) {
            throw new ApiException('Este tipo de documento es del sistema y no puede modificarse.', 422);
        }

        return $this->documentTypeRepository->createOrUpdate($data);
    }

    public function delete(string $code)
    {
        $record = DocumentType::findOrFail($code);
        if ($record->is_system) {
            throw new ApiException('Este tipo de documento es del sistema y no puede eliminarse.', 422);
        }

        return $this->documentTypeRepository->delete($code);
    }

    public function getSelectItems()
    {
        return $this->documentTypeRepository->getSelectItems();
    }
}
