<?php

namespace App\Modules\Learning\Learner\Certification\Services;

use App\Models\Learning\Certification;
use App\Modules\Learning\Learner\Support\LearnerContext;
use Illuminate\Support\Collection;

class CertificationService
{
    public function __construct(
        private LearnerContext $learnerContext,
    ) {}

    public function list(): Collection
    {
        $workerId = $this->learnerContext->workerId();

        return Certification::whereHas('enrollment', fn ($q) => $q->where('worker_id', $workerId))
            ->with([
                'enrollment.enrollable',
                'template:id,name',
            ])
            ->orderByDesc('issued_at')
            ->get()
            ->map(fn (Certification $c) => [
                'id'                 => $c->id,
                'certificateNumber'  => $c->certificate_number,
                'issuedAt'           => $c->issued_at,
                'expiresAt'          => $c->expires_at,
                'template'           => $c->template ? ['id' => $c->template->id, 'name' => $c->template->name] : null,
                'courseName'         => $this->resolveName($c),
                'enrollmentId'       => $c->enrollment_id,
            ]);
    }

    private function resolveName(Certification $certification): ?string
    {
        $enrollment = $certification->enrollment;
        if (!$enrollment || !$enrollment->enrollable) return null;

        return match ($enrollment->enrollable_type) {
            'learning_courses'            => $enrollment->enrollable->name,
            'learning_trainings'          => $enrollment->enrollable->course?->name ?? 'Capacitación',
            'learning_program_deliveries' => $enrollment->enrollable->program?->name ?? 'Programa',
            default                       => null,
        };
    }
}
