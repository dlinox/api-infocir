<?php

namespace App\Modules\SupplierPanel\Setting\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\SupplierPanel\Setting\Http\Requests\SettingRequest;
use App\Modules\SupplierPanel\Setting\Http\Resources\SettingResource;
use App\Modules\SupplierPanel\Setting\Services\SettingService;

class SettingController
{
    public function __construct(
        private SettingService $service,
    ) {}

    public function getCurrent()
    {
        $supplier = $this->service->getCurrent();
        return ApiResponse::success(new SettingResource($supplier));
    }

    public function save(SettingRequest $request)
    {
        $supplier = $this->service->save($request->validated());
        return ApiResponse::success(new SettingResource($supplier), 'Ajustes guardados correctamente');
    }
}
