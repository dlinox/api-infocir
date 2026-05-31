<?php

namespace App\Modules\Admin\Dairy\Organization\Worker\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Behavior\Role;
use App\Models\Core\Profile;
use App\Models\Dairy\Worker;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Shared\Repositories\ProfileRepository;
use Illuminate\Support\Facades\DB;

class CreateOrUpdateWorkerAction
{
    public function __construct(
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
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
        $person = $this->createOrUpdatePersonAction->execute($data['person']);

        if (Worker::where('person_id', $person->id)->exists()) {
            throw new ApiException('La persona ya es un trabajador registrado');
        }

        $worker = Worker::create([
            'person_id'             => $person->id,
            'entity_id'             => $data['entity_id'],
            'position_id'           => $data['position_id'] ?? null,
            'instruction_degree_id' => $data['instruction_degree_id'] ?? null,
            'profession_id'         => $data['profession_id'] ?? null,
            'monthly_salary'        => $data['monthly_salary'],
            'is_active'             => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::create([
            'person_id'        => $person->id,
            'profileable_type' => 'dairy_workers',
            'profileable_id'   => $person->id,
        ]);

        $workerRole = Role::where('name', Worker::ROLE_NAME)->firstOrFail();
        $this->profileRepository->create($person->user_id, $workerRole->id, $coreProfile->id);

        $positionRoleId = !empty($data['position_id'])
            ? \App\Models\Dairy\Position::find($data['position_id'])?->role_id
            : null;
        if ($positionRoleId && $positionRoleId !== $workerRole->id) {
            $this->profileRepository->create($person->user_id, $positionRoleId, $coreProfile->id);
        }

        return $worker;
    }

    private function update(array $data): Worker
    {
        $person = $this->createOrUpdatePersonAction->execute($data['person']);

        $worker = Worker::where('person_id', $person->id)->firstOrFail();

        $worker->update([
            'entity_id'             => $data['entity_id'],
            'position_id'           => $data['position_id'] ?? null,
            'instruction_degree_id' => $data['instruction_degree_id'] ?? null,
            'profession_id'         => $data['profession_id'] ?? null,
            'monthly_salary'        => $data['monthly_salary'],
            'is_active'             => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::where('person_id', $person->id)
            ->where('profileable_type', 'dairy_workers')
            ->first();

        $positionRoleId = !empty($data['position_id'])
            ? \App\Models\Dairy\Position::find($data['position_id'])?->role_id
            : null;

        if ($coreProfile && $positionRoleId) {
            $workerRoleId = Role::where('name', Worker::ROLE_NAME)->value('id');
            $existingPositionProfile = BehaviorProfile::where('core_profile_id', $coreProfile->id)
                ->where('role_id', '!=', $workerRoleId)
                ->first();

            if ($existingPositionProfile) {
                $existingPositionProfile->update(['role_id' => $positionRoleId]);
            } elseif ($person->user_id) {
                $this->profileRepository->create($person->user_id, $positionRoleId, $coreProfile->id);
            }
        }

        return $worker;
    }
}
