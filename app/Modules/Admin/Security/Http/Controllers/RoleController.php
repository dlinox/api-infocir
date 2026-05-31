<?php

namespace App\Modules\Admin\Security\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Security\Http\Requests\Role\RoleRequest;
use App\Modules\Admin\Security\Http\Resources\Role\RoleDataTableItemResource;
use App\Modules\Admin\Security\Http\Resources\Role\RoleFormResource;
use App\Modules\Admin\Security\Http\Resources\Role\RoleSelectItemResource;
use App\Modules\Admin\Security\Services\RoleService;

class RoleController
{
    public function __construct(
        private RoleService $roleService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->roleService->dataTable($request);
        $items['data'] = RoleDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function get(int $id)
    {
        $role = $this->roleService->findById($id);
        return ApiResponse::success(new RoleFormResource($role));
    }

    public function save(RoleRequest $request)
    {
        $data = $request->validated();
        $this->roleService->save($data);
        return ApiResponse::success(null, 'Rol guardado correctamente');
    }

    public function delete(string $id)
    {
        $this->roleService->delete($id);
        return ApiResponse::success(null, 'Rol eliminado correctamente');
    }

    public function getSelectItems()
    {
        $items = $this->roleService->getSelectItems();
        return ApiResponse::success(RoleSelectItemResource::collection($items));
    }
}
