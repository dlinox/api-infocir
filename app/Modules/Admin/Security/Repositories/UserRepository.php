<?php

namespace App\Modules\Admin\Security\Repositories;

use App\Models\Auth\User;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Person;
use App\Models\Core\Profile;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function dataTable($request)
    {
        $filters = is_array($request->filters) ? $request->filters : [];

        $roleId = $filters['role_id'] ?? null;
        $scope  = $filters['scope'] ?? null;
        unset($filters['role_id'], $filters['scope']);
        $request->merge(['filters' => $filters]);

        $query = User::query()
            ->select(
                'auth_users.*',
                'core_persons.id as person_id',
                'core_persons.document_number as person_document_number',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
            )
            ->leftJoin('core_persons', 'core_persons.user_id', '=', 'auth_users.id')
            ->with('profiles.role');

        if ($roleId) {
            $query->whereHas('profiles', fn ($q) => $q->where('role_id', $roleId));
        }

        if ($scope) {
            $query->whereHas('profiles.role', fn ($q) => $q->where('scope', $scope));
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('auth_users.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): User
    {
        return User::with(['profiles.role', 'profiles.coreProfile', 'person'])->findOrFail($id);
    }

    public function createOrUpdate(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $personId = $data['person_id'] ?? null;

            $userData = [
                'username'  => $data['username'],
                'email'     => $data['email'] ?? null,
                'is_active' => $data['is_active'],
            ];

            if (!empty($data['password'])) {
                $userData['password'] = $data['password'];
            }

            if (isset($data['id'])) {
                $user = User::findOrFail($data['id']);
                $user->update($userData);
            } else {
                $user = User::create($userData);
            }

            if ($personId) {
                Person::where('id', $personId)->update(['user_id' => $user->id]);
            }

            return $user;
        });
    }

    public function resetPassword(int $id, string $password): User
    {
        $user = User::findOrFail($id);
        $user->update(['password' => $password]);
        return $user;
    }

    public function toggleActive(int $id): User
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        return $user;
    }

    public function syncProfiles(int $userId, array $profiles): void
    {
        DB::transaction(function () use ($userId, $profiles) {
            $keepIds = [];

            foreach ($profiles as $profile) {
                $attributes = [
                    'role_id'         => $profile['role_id'],
                    'core_profile_id' => $profile['core_profile_id'],
                    'is_active'       => $profile['is_active'] ?? true,
                ];

                if (!empty($profile['id'])) {
                    $behaviorProfile = BehaviorProfile::where('user_id', $userId)
                        ->where('id', $profile['id'])
                        ->first();
                    if ($behaviorProfile) {
                        $behaviorProfile->update($attributes);
                        $keepIds[] = $behaviorProfile->id;
                        continue;
                    }
                }

                $behaviorProfile = BehaviorProfile::create(array_merge(
                    ['user_id' => $userId],
                    $attributes
                ));
                $keepIds[] = $behaviorProfile->id;
            }

            BehaviorProfile::where('user_id', $userId)
                ->whereNotIn('id', $keepIds ?: [0])
                ->delete();
        });
    }

    public function getCoreProfiles(int $userId)
    {
        $person = Person::where('user_id', $userId)->first();

        if (!$person) {
            return collect();
        }

        return Profile::where('person_id', $person->id)
            ->with('profileable')
            ->get();
    }
}
