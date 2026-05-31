<?php

namespace App\Modules\Admin\Security\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Admin\Security\Http\Requests\User\AssignProfilesRequest;
use App\Modules\Admin\Security\Http\Requests\User\ResetPasswordRequest;
use App\Modules\Admin\Security\Http\Requests\User\UserRequest;
use App\Modules\Admin\Security\Http\Resources\Session\SessionDataTableItemResource;
use App\Modules\Admin\Security\Http\Resources\User\UserCoreProfileResource;
use App\Modules\Admin\Security\Http\Resources\User\UserDataTableItemResource;
use App\Modules\Admin\Security\Http\Resources\User\UserFormResource;
use App\Modules\Admin\Security\Services\UserSecurityService;

class UserController
{
    public function __construct(
        private UserSecurityService $userSecurityService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->userSecurityService->dataTable($request);
        $items['data'] = UserDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function get(int $id)
    {
        $user = $this->userSecurityService->findById($id);
        return ApiResponse::success(new UserFormResource($user));
    }

    public function save(UserRequest $request)
    {
        $data = $request->validated();
        $this->userSecurityService->save($data);
        return ApiResponse::success(null, 'Usuario guardado correctamente');
    }

    public function resetPassword(ResetPasswordRequest $request, int $id)
    {
        $this->userSecurityService->resetPassword($id, $request->validated()['password']);
        return ApiResponse::success(null, 'Contraseña actualizada correctamente');
    }

    public function toggleActive(int $id)
    {
        $this->userSecurityService->toggleActive($id);
        return ApiResponse::success(null, 'Estado del usuario actualizado correctamente');
    }

    public function assignProfiles(AssignProfilesRequest $request, int $id)
    {
        $this->userSecurityService->assignProfiles($id, $request->validated()['profiles']);
        return ApiResponse::success(null, 'Perfiles actualizados correctamente');
    }

    public function coreProfiles(int $id)
    {
        $items = $this->userSecurityService->getCoreProfiles($id);
        return ApiResponse::success(UserCoreProfileResource::collection($items));
    }

    public function sessions(int $id)
    {
        $items = $this->userSecurityService->getSessions($id);
        return ApiResponse::success(SessionDataTableItemResource::collection($items));
    }

    public function revokeAllSessions(int $id)
    {
        $this->userSecurityService->revokeAllSessions($id);
        return ApiResponse::success(null, 'Sesiones revocadas correctamente');
    }
}
