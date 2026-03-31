<?php

namespace App\Modules\Admin\Setting\Repositories;

use App\Models\Core\DocumentType;

class DocumentTypeRepository
{
    public function dataTable($request)
    {
        $query = DocumentType::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('code', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return DocumentType::updateOrCreate(['code' => $data['code']], $data);
    }

    public function delete(string $code)
    {
        $documentType = DocumentType::where('code', $code)->firstOrFail();
        $documentType->delete();
        return $documentType;
    }

    public function getSelectItems()
    {
        return DocumentType::where('is_active', true)->orderBy('name')->get();
    }
}
