<?php

namespace App\Modules\Learning\Instructor\Scheduling\Certification\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Instructor\Scheduling\Certification\Http\Resources\Certification\CertificationDataTableItemResource;
use App\Modules\Learning\Instructor\Scheduling\Certification\Services\CertificationService;

class CertificationController
{
    public function __construct(
        private CertificationService $certificationService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->certificationService->dataTable($request);
        $items['data'] = CertificationDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }
}
