<?php

namespace App\Modules\Admin\Security\Services;

use Illuminate\Http\Request;
use App\Models\Auth\User;
use App\Modules\Admin\Security\Repositories\SessionRepository;
use App\Modules\Admin\Security\Repositories\UserRepository;

class UserSecurityService
{
    public function __construct(
        private UserRepository $userRepository,
        private SessionRepository $sessionRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->userRepository->dataTable($request);
    }

    public function findById(int $id): User
    {
        return $this->userRepository->findById($id);
    }

    public function save(array $data): User
    {
        return $this->userRepository->createOrUpdate($data);
    }

    public function resetPassword(int $id, string $password): User
    {
        return $this->userRepository->resetPassword($id, $password);
    }

    public function toggleActive(int $id): User
    {
        return $this->userRepository->toggleActive($id);
    }

    public function assignProfiles(int $userId, array $profiles): void
    {
        $this->userRepository->syncProfiles($userId, $profiles);
    }

    public function getCoreProfiles(int $userId)
    {
        return $this->userRepository->getCoreProfiles($userId);
    }

    public function getSessions(int $userId)
    {
        return $this->sessionRepository->getForUser($userId);
    }

    public function revokeAllSessions(int $userId): void
    {
        $this->sessionRepository->revokeAllForUser($userId);
    }
}
