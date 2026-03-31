<?php

namespace App\Modules\Shared\Http\Controllers;

use Illuminate\Http\JsonResponse;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Shared\Http\Requests\Profile\UpdatePersonalRequest;
use App\Modules\Shared\Http\Requests\Profile\ChangePasswordRequest;
use App\Modules\Shared\Http\Resources\Profile\ProfileMeResource;
use App\Modules\Shared\Services\ProfileService;

class ProfileController
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    public function me(): JsonResponse
    {
        $data = $this->profileService->getMe();

        return ApiResponse::success(
            new ProfileMeResource($data['user'], $data['person'], $data['profile'])
        );
    }

    public function updatePersonal(UpdatePersonalRequest $request): JsonResponse
    {
        $this->profileService->updatePersonal($request->validated());

        return ApiResponse::success(null, 'Datos personales actualizados correctamente');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->profileService->changePassword(
            $data['current_password'],
            $data['new_password']
        );

        return ApiResponse::success(null, 'Contraseña actualizada correctamente');
    }
}
