<?php

namespace App\Modules\Learning\Learner\Enrollment\Repositories;

use App\Models\Learning\Enrollment;
use Illuminate\Support\Collection;

class EnrollmentRepository
{
    public function listForWorker(int $workerId): Collection
    {
        $enrollments = Enrollment::where('worker_id', $workerId)
            ->with([
                'enrollable',
                'certification:id,enrollment_id,certificate_number,issued_at,expires_at',
            ])
            ->orderByDesc('enrolled_at')
            ->get();

        $this->loadEnrollableRelations($enrollments);

        return $enrollments;
    }

    public function findForWorker(int $enrollmentId, int $workerId): ?Enrollment
    {
        $enrollment = Enrollment::where('id', $enrollmentId)
            ->where('worker_id', $workerId)
            ->with([
                'enrollable',
                'certification',
            ])
            ->first();

        if ($enrollment) {
            $this->loadEnrollableRelations(collect([$enrollment]));
        }

        return $enrollment;
    }

    private function loadEnrollableRelations(Collection $enrollments): void
    {
        foreach ($enrollments as $enrollment) {
            $enrollable = $enrollment->enrollable;
            if (!$enrollable) continue;

            match ($enrollment->enrollable_type) {
                'learning_courses' => $enrollable->loadMissing([
                    'area:id,name',
                    'coverImageFile',
                ]),
                'learning_trainings' => $enrollable->loadMissing([
                    'course:id,name,description,duration_min,cover_image',
                    'course.coverImageFile',
                    'trainingType:id,name',
                    'instructor.person:id,name,paternal_surname,maternal_surname',
                ]),
                'learning_program_deliveries' => $enrollable->loadMissing([
                    'program:id,name,description',
                    'trainingType:id,name',
                    'instructor.person:id,name,paternal_surname,maternal_surname',
                ]),
                default => null,
            };
        }
    }
}
