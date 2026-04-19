<?php

namespace App\Modules\Learning\Instructor\Scheduling\Certification\Services;

use Illuminate\Http\Request;
use App\Modules\Learning\Instructor\Scheduling\Certification\Repositories\CertificationRepository;

class CertificationService
{
    public function __construct(
        private CertificationRepository $certificationRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->certificationRepository->dataTable($request);
    }
}
