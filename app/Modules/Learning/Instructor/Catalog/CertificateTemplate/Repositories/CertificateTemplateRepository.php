<?php

namespace App\Modules\Learning\Instructor\Catalog\CertificateTemplate\Repositories;

use App\Models\Learning\CertificateTemplate;
use App\Models\Learning\Course;
use App\Models\Learning\Program;
use App\Models\Learning\Training;

class CertificateTemplateRepository
{
    public function dataTable($request)
    {
        $query = CertificateTemplate::query();

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getSelectItems()
    {
        return CertificateTemplate::where('is_active', true)->orderBy('name')->get();
    }

    public function findByEntity(string $entityType, int $entityId): ?CertificateTemplate
    {
        $entity = match ($entityType) {
            'course'   => Course::with('certificateTemplate')->findOrFail($entityId),
            'program'  => Program::with('certificateTemplate')->findOrFail($entityId),
            'training' => Training::with('certificateTemplate')->findOrFail($entityId),
        };

        return $entity->certificateTemplate;
    }

    public function findById(int $templateId): CertificateTemplate
    {
        return CertificateTemplate::with('backgroundFile', 'signatures')->findOrFail($templateId);
    }

    public function createOrUpdate(array $data, string $entityType, int $entityId): CertificateTemplate
    {
        $modelClass = match ($entityType) {
            'course'   => Course::class,
            'program'  => Program::class,
            'training' => Training::class,
        };

        $entity = $modelClass::findOrFail($entityId);

        if ($entity->certificate_template_id) {
            $template = CertificateTemplate::findOrFail($entity->certificate_template_id);
            $template->update($data);
        } else {
            $template = CertificateTemplate::create($data);
            $entity->update(['certificate_template_id' => $template->id]);
        }

        return $template->fresh();
    }

    public function updateTemplate(int $templateId, array $data): CertificateTemplate
    {
        $template = CertificateTemplate::findOrFail($templateId);
        $template->update($data);
        return $template->fresh();
    }
}
