<?php

namespace App\Modules\Learning\Learner\Enrollment\Services;

use App\Common\Exceptions\ApiException;
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
