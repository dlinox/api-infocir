<?php

namespace App\Modules\Learning\Instructor\Scheduling\Certification\Repositories;

use App\Models\Learning\Certification;

class CertificationRepository
{
    public function dataTable($request)
    {
        $query = Certification::query()
            ->select(
                'learning_certifications.*',
                'learning_certificate_templates.name as template_name',
                'core_persons.name as worker_name',
                'core_persons.paternal_surname as worker_paternal_surname',
                'core_persons.maternal_surname as worker_maternal_surname',
            )
            ->leftJoin('learning_enrollments', 'learning_enrollments.id', '=', 'learning_certifications.enrollment_id')
            ->leftJoin('dairy_workers', 'dairy_workers.person_id', '=', 'learning_enrollments.worker_id')
            ->leftJoin('core_persons', 'core_persons.id', '=', 'dairy_workers.person_id')
            ->leftJoin('learning_certificate_templates', 'learning_certificate_templates.id', '=', 'learning_certifications.template_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('learning_certifications.id', 'desc');
        }

        return $query->dataTable($request);
    }
}
