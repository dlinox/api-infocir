<?php

namespace App\Modules\Learning\Learner\Certification\Services;

use App\Models\Core\CoreFile;
use App\Models\Learning\Certification;
use App\Modules\Learning\Learner\Support\LearnerContext;
use Illuminate\Support\Facades\Storage;
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

    public function getPreview(int $id): array
    {
        $workerId = $this->learnerContext->workerId();

        $certification = Certification::whereHas('enrollment', fn ($q) => $q->where('worker_id', $workerId))
            ->with([
                'enrollment.enrollable',
                'enrollment.worker.person',
                'template.backgroundFile',
                'template.signatures.signatureFile',
            ])
            ->findOrFail($id);

        $person = $certification->enrollment?->worker?->person;
        $fullName = collect([$person?->name, $person?->paternal_surname, $person?->maternal_surname])
            ->filter()
            ->implode(' ');

        $template = $certification->template;

        return [
            'id'                => $certification->id,
            'certificateNumber' => $certification->certificate_number,
            'issuedAt'          => $certification->issued_at,
            'expiresAt'         => $certification->expires_at,
            'courseName'        => $this->resolveName($certification),
            'workerName'        => $fullName,
            'template'          => $template ? [
                'id'         => $template->id,
                'fields'     => $template->fields,
                'background' => $template->backgroundFile
                    ? ['url' => $this->toDataUrl($template->backgroundFile)]
                    : null,
                'signatures' => $template->signatures->map(fn ($s) => [
                    'id'       => $s->id,
                    'title'    => $s->title,
                    'subtitle' => $s->subtitle,
                    'x'        => $s->x,
                    'y'        => $s->y,
                    'width'    => $s->width,
                    'image'    => $s->signatureFile
                        ? ['url' => $this->toDataUrl($s->signatureFile)]
                        : null,
                ])->values(),
            ] : null,
        ];
    }

    private function toDataUrl(CoreFile $file): string
    {
        try {
            $content = Storage::disk($file->storage_disk)->get($file->filepath);
            $mime = $file->mime_type ?? 'image/jpeg';
            return 'data:' . $mime . ';base64,' . base64_encode($content);
        } catch (\Throwable) {
            return $file->url ?? '';
        }
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
