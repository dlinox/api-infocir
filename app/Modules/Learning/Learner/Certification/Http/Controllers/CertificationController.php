<?php

namespace App\Modules\Learning\Learner\Certification\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Learner\Certification\Services\CertificationService;
use Illuminate\Http\JsonResponse;

class CertificationController
{
    public function __construct(
        private CertificationService $certificationService,
    ) {}

    public function list(): JsonResponse
    {
        $data = $this->certificationService->list();

        return ApiResponse::success($data);
    }

    public function preview(int $id): JsonResponse
    {
        $data = $this->certificationService->getPreview($id);

        return ApiResponse::success($data);
    }
}
