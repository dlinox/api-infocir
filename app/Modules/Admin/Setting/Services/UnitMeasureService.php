<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Common\Exceptions\ApiException;
use App\Models\Core\UnitMeasure;
use App\Modules\Admin\Setting\Repositories\UnitMeasureRepository;

class UnitMeasureService
{
    public function __construct(
        private UnitMeasureRepository $unitMeasureRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->unitMeasureRepository->dataTable($request);
    }

    public function save(array $data)
    {
        unset($data['is_system']);

        if (isset($data['id'])) {
            $existing = UnitMeasure::findOrFail($data['id']);
            if ($existing->is_system) {
                $existing->update(['is_active' => $data['is_active']]);
                return $existing;
            }
        }

        return $this->unitMeasureRepository->createOrUpdate($data);
    }

    public function delete(string $id)
    {
        $record = UnitMeasure::findOrFail($id);
        if ($record->is_system) {
            throw new ApiException('Esta unidad de medida es del sistema y no puede eliminarse.', 422);
        }

        return $this->unitMeasureRepository->delete($id);
    }

    public function getSelectItems()
    {
        return $this->unitMeasureRepository->getSelectItems();
    }
}
