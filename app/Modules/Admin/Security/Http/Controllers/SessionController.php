<?php

namespace App\Modules\Admin\Security\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Security\Http\Resources\Session\SessionDataTableItemResource;
use App\Modules\Admin\Security\Services\SessionSecurityService;

class SessionController
{
    public function __construct(
        private SessionSecurityService $sessionSecurityService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->sessionSecurityService->dataTable($request);
        $items['data'] = SessionDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function revoke(int $id)
    {
        $this->sessionSecurityService->revoke($id);
        return ApiResponse::success(null, 'Sesión revocada correctamente');
    }
}
