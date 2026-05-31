<?php

namespace App\Modules\Admin\Security\Services;

use Illuminate\Http\Request;
use App\Modules\Admin\Security\Repositories\SessionRepository;

class SessionSecurityService
{
    public function __construct(
        private SessionRepository $sessionRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->sessionRepository->dataTable($request);
    }

    public function revoke(int $id): void
    {
        $this->sessionRepository->revoke($id);
    }
}
