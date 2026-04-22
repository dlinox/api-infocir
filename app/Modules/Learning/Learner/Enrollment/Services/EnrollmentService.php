<?php

namespace App\Modules\Learning\Learner\Enrollment\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Learning\Course;
use App\Models\Learning\Enrollment;
use App\Modules\Learning\Learner\Enrollment\Repositories\EnrollmentRepository;
use App\Modules\Learning\Learner\Support\LearnerContext;
use Illuminate\Support\Collection;

class EnrollmentService
{
    public function __construct(
        private EnrollmentRepository $enrollmentRepository,
        private LearnerContext $learnerContext,
    ) {}

    public function list(): Collection
    {
        return $this->enrollmentRepository->listForWorker(
            $this->learnerContext->workerId(),
        );
    }

    public function selfEnroll(int $courseId): Enrollment
    {
        $workerId = $this->learnerContext->workerId();

        $course = Course::where('id', $courseId)->where('status', 'published')->first();

        if (!$course) {
            throw new ApiException('El curso no existe o no está disponible.', 404);
        }

        $existing = Enrollment::where('worker_id', $workerId)
            ->where('enrollable_type', 'learning_courses')
            ->where('enrollable_id', $courseId)
            ->first();

        if ($existing) {
            throw new ApiException('Ya estás matriculado en este curso.', 422);
        }

        $enrollment = Enrollment::create([
            'enrollable_type' => 'learning_courses',
            'enrollable_id'   => $courseId,
            'worker_id'       => $workerId,
            'status'          => 'enrolled',
            'progress'        => 0,
            'enrolled_at'     => now(),
        ]);

        $enrollment->load(['enrollable', 'certification']);
        $enrollment->enrollable?->loadMissing(['area:id,name', 'coverImageFile']);

        return $enrollment;
    }

    public function find(int $enrollmentId): Enrollment
    {
        $enrollment = $this->enrollmentRepository->findForWorker(
            $enrollmentId,
            $this->learnerContext->workerId(),
        );

        if (!$enrollment) {
            throw new ApiException('Inscripción no encontrada.', 404);
        }

        return $enrollment;
    }
}
