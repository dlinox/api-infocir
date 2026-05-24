<?php

namespace App\Modules\Admin\Setting\Services;

use Illuminate\Http\Request;
use App\Common\Exceptions\ApiException;
use App\Models\Core\Gender;
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
        unset($data['is_system']);

        $existing = Gender::find($data['code']);
        if ($existing?->is_system) {
            $existing->update(['is_active' => $data['is_active']]);
            return $existing;
        }

        return $this->genderRepository->createOrUpdate($data);
    }

    public function delete(string $code)
    {
        $record = Gender::findOrFail($code);
        if ($record->is_system) {
            throw new ApiException('Este género es del sistema y no puede eliminarse.', 422);
        }

        return $this->genderRepository->delete($code);
    }

    public function getSelectItems()
    {
        return $this->genderRepository->getSelectItems();
    }
}
