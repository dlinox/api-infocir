<?php

namespace App\Modules\Admin\Security\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Security\Http\Requests\Permission\PermissionRequest;
use App\Modules\Admin\Security\Http\Resources\Permission\PermissionDataTableItemResource;
use App\Modules\Admin\Security\Http\Resources\Permission\PermissionFormResource;
use App\Modules\Admin\Security\Http\Resources\Permission\PermissionSelectItemResource;
use App\Modules\Admin\Security\Services\PermissionService;

class PermissionController
{
    public function __construct(
        private PermissionService $permissionService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->permissionService->dataTable($request);
        $items['data'] = PermissionDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function get(int $id)
    {
        $permission = $this->permissionService->findById($id);
        return ApiResponse::success(new PermissionFormResource($permission));
    }

    public function save(PermissionRequest $request)
    {
        $data = $request->validated();
        $this->permissionService->save($data);
        return ApiResponse::success(null, 'Permiso guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->permissionService->delete($id);
        return ApiResponse::success(null, 'Permiso eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->permissionService->getSelectItems();
        return ApiResponse::success(PermissionSelectItemResource::collection($items));
    }
}
