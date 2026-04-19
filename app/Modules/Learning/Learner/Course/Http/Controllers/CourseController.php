<?php

namespace App\Modules\Learning\Learner\Course\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Learning\Learner\Course\Services\CourseService;
use Illuminate\Http\JsonResponse;

class CourseController
{
    public function __construct(
        private CourseService $courseService,
    ) {}

    public function content(int $enrollmentId): JsonResponse
    {
        $data = $this->courseService->getContent($enrollmentId);

        return ApiResponse::success($data);
    }
}
