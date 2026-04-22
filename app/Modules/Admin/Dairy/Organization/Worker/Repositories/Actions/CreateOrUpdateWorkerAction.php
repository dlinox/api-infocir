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
            $personId = $personData['id'] ?? null;

            $isUpdate = $personId && Worker::where('person_id', $personId)->exists();

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

        $person = $this->createOrUpdatePersonAction->execute($personData);

        $existingWorker = Worker::where('person_id', $person->id)->first();
        if ($existingWorker) {
            throw new ApiException('La persona ya es un trabajador registrado');
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
            'person_id'            => $person->id,
            'entity_id'            => $data['entity_id'],
            'position_id'          => $data['position_id'] ?? null,
            'instruction_degree_id' => $data['instruction_degree_id'] ?? null,
            'profession_id'        => $data['profession_id'] ?? null,
            'monthly_salary'       => $data['monthly_salary'],
            'is_active'            => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::create([
            'person_id'        => $person->id,
            'profileable_type' => 'dairy_workers',
            'profileable_id'   => $person->id,
        ]);

        $workerRole = Role::where('name', Worker::ROLE_NAME)->firstOrFail();
        $this->profileRepository->create($userId, $workerRole->id, $coreProfile->id);

        if (!empty($data['role_id']) && $data['role_id'] !== $workerRole->id) {
            $this->profileRepository->create($userId, $data['role_id'], $coreProfile->id);
        }

        return $worker;
    }

    private function update(array $data): Worker
    {
        $personData = $data['person'];

        $person = $this->createOrUpdatePersonAction->execute($personData);

        $worker = Worker::where('person_id', $person->id)->firstOrFail();

        $worker->update([
            'entity_id'            => $data['entity_id'],
            'position_id'          => $data['position_id'] ?? null,
            'instruction_degree_id' => $data['instruction_degree_id'] ?? null,
            'profession_id'        => $data['profession_id'] ?? null,
            'monthly_salary'       => $data['monthly_salary'],
            'is_active'            => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::where('person_id', $person->id)
            ->where('profileable_type', 'dairy_workers')
            ->first();
        if ($coreProfile && !empty($data['role_id'])) {
            $workerRoleId = Role::where('name', Worker::ROLE_NAME)->value('id');
            BehaviorProfile::where('core_profile_id', $coreProfile->id)
                ->where('role_id', '!=', $workerRoleId)
                ->update(['role_id' => $data['role_id']]);
        }

        $userId = $this->findExistingUserId($person->id);

        if ($userId) {
            $this->createOrUpdateUserAction->execute([
                'id'       => $userId,
                'username' => $person->document_number,
                'email'    => $person->email,
            ]);
        }

        return $worker;
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
