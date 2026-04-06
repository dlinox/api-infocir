<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Behavior\Role;
use App\Models\Core\Profile;
use App\Models\Dairy\Worker;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Shared\Repositories\ProfileRepository;
use Illuminate\Support\Facades\DB;

class CreateOrUpdateWorkerAction
{
    public function __construct(
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
        private ProfileRepository $profileRepository,
    ) {}

    public function execute(array $data): Worker
    {
        try {
            DB::beginTransaction();

            $personData = $data['person'];
            $isUpdate = !empty($personData['id']);

            $result = $isUpdate ? $this->update($data) : $this->create($data);

            DB::commit();

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function create(array $data): Worker
    {
        $personData = $data['person'];
        $isManager = (bool) ($data['is_manager'] ?? false);

        $person = $this->createOrUpdatePersonAction->execute($personData);

        $existingWorker = Worker::where('person_id', $person->id)->first();
        if ($existingWorker) {
            throw new ApiException('La persona ya es un trabajador de planta');
        }

        $userId = $this->findExistingUserId($person->id);

        if (!$userId) {
            $user = $this->createOrUpdateUserAction->execute([
                'username' => $person->document_number,
                'password' => $person->document_number,
                'email' => $person->email,
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        $worker = Worker::create([
            'person_id' => $person->id,
            'plant_id' => $data['plant_id'],
            'position_id' => $data['position_id'] ?? null,
            'instruction_degree_id' => $data['instruction_degree_id'] ?? null,
            'profession_id' => $data['profession_id'] ?? null,
            'is_manager' => $isManager,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::create([
            'person_id' => $person->id,
            'profileable_type' => 'dairy_plant_workers',
            'profileable_id' => $person->id,
        ]);

        $plantWorkerRole = Role::where('name', 'plant_worker')->firstOrFail();
        $this->profileRepository->create($userId, $plantWorkerRole->id, $coreProfile->id);

        if ($isManager) {
            $plantManagerRole = Role::where('name', 'plant_manager')->firstOrFail();
            $this->profileRepository->create($userId, $plantManagerRole->id, $coreProfile->id);
        }

        return $worker;
    }

    private function update(array $data): Worker
    {
        $personData = $data['person'];
        $isManager = (bool) ($data['is_manager'] ?? false);

        $person = $this->createOrUpdatePersonAction->execute($personData);

        $worker = Worker::where('person_id', $person->id)->firstOrFail();

        $worker->update([
            'plant_id' => $data['plant_id'],
            'position_id' => $data['position_id'] ?? null,
            'instruction_degree_id' => $data['instruction_degree_id'] ?? null,
            'profession_id' => $data['profession_id'] ?? null,
            'is_manager' => $isManager,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $userId = $this->findExistingUserId($person->id);

        if ($userId) {
            $this->createOrUpdateUserAction->execute([
                'id' => $userId,
                'username' => $person->document_number,
                'email' => $person->email,
            ]);

            $this->manageManagerRole($userId, $person->id, $isManager);
        }

        return $worker;
    }

    private function manageManagerRole(int $userId, int $personId, bool $isManager): void
    {
        $plantManagerRole = Role::where('name', 'plant_manager')->first();
        if (!$plantManagerRole) return;

        $coreProfile = Profile::where('person_id', $personId)
            ->where('profileable_type', 'dairy_plant_workers')
            ->first();

        if (!$coreProfile) return;

        $existingManagerProfile = BehaviorProfile::where('user_id', $userId)
            ->where('role_id', $plantManagerRole->id)
            ->where('core_profile_id', $coreProfile->id)
            ->first();

        if ($isManager && !$existingManagerProfile) {
            $this->profileRepository->create($userId, $plantManagerRole->id, $coreProfile->id);
        }

        if (!$isManager && $existingManagerProfile) {
            $existingManagerProfile->delete();
        }
    }

    private function findExistingUserId(int $personId): ?int
    {
        $result = DB::table('behavior_profiles')
            ->join('core_profiles', 'core_profiles.id', '=', 'behavior_profiles.core_profile_id')
            ->where('core_profiles.person_id', $personId)
            ->select('behavior_profiles.user_id')
            ->first();

        return $result?->user_id;
    }
}
