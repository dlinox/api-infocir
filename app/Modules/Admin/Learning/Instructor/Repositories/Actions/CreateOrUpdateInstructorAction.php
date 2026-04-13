<?php

namespace App\Modules\Admin\Learning\Instructor\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Behavior\Role;
use App\Models\Core\Profile;
use App\Models\Learning\Instructor;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;
use App\Modules\Shared\Repositories\ProfileRepository;
use Illuminate\Support\Facades\DB;

class CreateOrUpdateInstructorAction
{
    public function __construct(
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
        private ProfileRepository $profileRepository,
    ) {}

    public function execute(array $data): Instructor
    {
        try {
            DB::beginTransaction();

            $personData = $data['person'];
            $personId = $personData['id'] ?? null;

            $isUpdate = $personId && Instructor::where('person_id', $personId)->exists();

            $result = $isUpdate ? $this->update($data) : $this->create($data);

            DB::commit();

            return $result;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function create(array $data): Instructor
    {
        $person = $this->createOrUpdatePersonAction->execute($data['person']);

        if (Instructor::where('person_id', $person->id)->exists()) {
            throw new ApiException('La persona ya es un instructor registrado');
        }

        $userId = $this->findExistingUserId($person->id);

        if (!$userId) {
            $user = $this->createOrUpdateUserAction->execute([
                'username'  => $person->document_number,
                'password'  => $person->document_number,
                'email'     => $person->email,
                'is_active' => true,
            ]);
            $userId = $user->id;
        }

        $instructor = Instructor::create([
            'person_id' => $person->id,
            'is_active' => $data['is_active'] ?? true,
        ]);

        $coreProfile = Profile::create([
            'person_id'        => $person->id,
            'profileable_type' => 'learning_instructors',
            'profileable_id'   => $instructor->id,
        ]);

        $role = Role::where('name', Instructor::ROLE_NAME)->firstOrFail();
        $this->profileRepository->create($userId, $role->id, $coreProfile->id);

        return $instructor;
    }

    private function update(array $data): Instructor
    {
        $person = $this->createOrUpdatePersonAction->execute($data['person']);

        $instructor = Instructor::where('person_id', $person->id)->firstOrFail();

        $instructor->update([
            'is_active' => $data['is_active'] ?? true,
        ]);

        $userId = $this->findExistingUserId($person->id);

        if ($userId) {
            $this->createOrUpdateUserAction->execute([
                'id'       => $userId,
                'username' => $person->document_number,
                'email'    => $person->email,
            ]);
        }

        return $instructor;
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
