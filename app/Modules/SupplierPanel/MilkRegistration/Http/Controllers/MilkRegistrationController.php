<?php

namespace App\Modules\SupplierPanel\MilkRegistration\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\SupplierPanel\MilkRegistration\Http\Requests\MilkRegistrationRequest;
use App\Modules\SupplierPanel\MilkRegistration\Http\Resources\MilkRegistrationDataTableItemResource;
use App\Modules\SupplierPanel\MilkRegistration\Http\Resources\MilkRegistrationFormResource;
use App\Modules\SupplierPanel\MilkRegistration\Services\MilkRegistrationService;
use Illuminate\Http\Request;

class MilkRegistrationController
{
    public function __construct(
        private MilkRegistrationService $service,
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->service->dataTable($request);
        $items['data'] = MilkRegistrationDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function getById(string $id)
    {
        $record = $this->service->findById((int) $id);
        return ApiResponse::success(new MilkRegistrationFormResource($record));
    }

    public function save(MilkRegistrationRequest $request)
    {
        $this->service->save($request->validated());
        return ApiResponse::success(null, 'Registro guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->service->delete((int) $id);
        return ApiResponse::success(null, 'Registro eliminado correctamente');
    }
}
