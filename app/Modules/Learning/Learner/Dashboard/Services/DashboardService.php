<?php

namespace App\Modules\Learning\Learner\Dashboard\Services;

use App\Models\Learning\Certification;
use App\Models\Learning\Enrollment;
use App\Modules\Learning\Learner\Support\LearnerContext;

class DashboardService
{
    public function __construct(
        private LearnerContext $learnerContext,
    ) {}

    public function getStats(): array
    {
        $workerId = $this->learnerContext->workerId();
        $worker = $this->learnerContext->worker();

        $enrollments = Enrollment::where('worker_id', $workerId);

        $inProgress = (clone $enrollments)->whereIn('status', ['enrolled', 'in_progress'])->count();
        $completed  = (clone $enrollments)->where('status', 'completed')->count();
        $total      = (clone $enrollments)->count();

        $certifications = Certification::whereHas('enrollment', fn ($q) => $q->where('worker_id', $workerId))->count();

        $avgProgress = (float) ((clone $enrollments)->avg('progress') ?? 0);

        $continueLearning = Enrollment::where('worker_id', $workerId)
            ->whereIn('status', ['enrolled', 'in_progress'])
            ->with(['enrollable'])
            ->orderByDesc('updated_at')
            ->limit(3)
            ->get()
            ->map(function (Enrollment $e) {
                $enrollable = $e->enrollable;
                $name = match ($e->enrollable_type) {
                    'learning_courses'            => $enrollable?->name,
                    'learning_trainings'          => $enrollable?->course?->name ?? 'Capacitación',
                    'learning_program_deliveries' => $enrollable?->program?->name ?? 'Programa',
                    default                       => null,
                };

                return [
                    'enrollmentId' => $e->id,
                    'name'         => $name,
                    'progress'     => (float) $e->progress,
                    'type'         => $e->enrollable_type,
                ];
            });

        return [
            'worker' => [
                'fullName' => collect([
                    $worker->person?->name,
                    $worker->person?->paternal_surname,
                    $worker->person?->maternal_surname,
                ])->filter()->implode(' '),
            ],
            'stats' => [
                'total'              => $total,
                'inProgress'         => $inProgress,
                'completed'          => $completed,
                'certifications'     => $certifications,
                'avgProgress'        => round($avgProgress, 2),
            ],
            'continueLearning' => $continueLearning,
        ];
    }
}
