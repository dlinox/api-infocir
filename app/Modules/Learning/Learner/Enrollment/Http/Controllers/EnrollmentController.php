<?php

namespace App\Modules\Learning\Learner\Enrollment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Learner\Enrollment\Http\Resources\Enrollment\EnrollmentListResource;
use App\Modules\Learning\Learner\Enrollment\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnrollmentController
{
    public function __construct(
        private EnrollmentService $enrollmentService,
    ) {}

    public function list(): JsonResponse
    {
        $enrollments = $this->enrollmentService->list();

        return ApiResponse::success(EnrollmentListResource::collection($enrollments));
    }

    public function selfEnroll(Request $request): JsonResponse
    {
        $courseId = (int) $request->input('courseId');
        $enrollment = $this->enrollmentService->selfEnroll($courseId);

        return ApiResponse::success(new EnrollmentListResource($enrollment), 'Matriculación exitosa.');
    }

    public function getById(int $id): JsonResponse
    {
        $enrollment = $this->enrollmentService->find($id);

        return ApiResponse::success(new EnrollmentListResource($enrollment));
    }
}
