<?php

namespace App\Modules\Shared\Repositories;

use App\Common\Exceptions\ApiException;
use App\Models\Core\Person;
use App\Models\Core\Profile;
use Illuminate\Support\Facades\DB;

class PersonRepository
{
    private const PROFILE_TYPE_MAP = [
        'admins'      => 'core_admins',
        'workers'     => 'dairy_workers',
        'instructors' => 'learning_instructors',
    ];

    private const PROFILE_TYPE_LABELS = [
        'core_admins'          => 'Administrador',
        'dairy_workers'        => 'Trabajador',
        'dairy_plants'         => 'Planta',
        'dairy_suppliers'      => 'Proveedor',
        'learning_instructors' => 'Instructor',
    ];

    public function searchByDocument(string $documentType, string $documentNumber, string $profile, ?int $id = null): ?array
    {
        $targetType = self::PROFILE_TYPE_MAP[$profile] ?? null;
        if (!$targetType) return null;

        $person = Person::where('document_type', $documentType)
            ->where('document_number', $documentNumber)
            ->first();

        if ($id) {
            if (!$person) return null;
            if ($person->id === $id) return null;
            throw new ApiException('El documento pertenece a otra persona');
        }

        if (!$person) return null;

        $existingProfiles = $this->loadProfiles($person->id);
        $profileAlreadyExists = collect($existingProfiles)
            ->contains(fn ($p) => $p['type'] === $targetType);

        $user = $person->user_id
            ? DB::table('auth_users')
                ->where('id', $person->user_id)
                ->select('id', 'username', 'email', 'is_active')
                ->first()
            : null;

        return [
            'exists'               => true,
            'profileAlreadyExists' => $profileAlreadyExists,
            'person'               => $person,
            'user'                 => $user,
            'profiles'             => $existingProfiles,
        ];
    }

    public function getById(int $id): ?Person
    {
        return Person::find($id);
    }

    private function loadProfiles(int $personId): array
    {
        $profiles = Profile::where('person_id', $personId)->get();

        return $profiles->map(function (Profile $profile) {
            $entityName = $this->resolveEntityName($profile->profileable_type, $profile->profileable_id);
            $roleNames  = DB::table('behavior_profiles')
                ->join('behavior_roles', 'behavior_roles.id', '=', 'behavior_profiles.role_id')
                ->where('behavior_profiles.core_profile_id', $profile->id)
                ->pluck('behavior_roles.display_name')
                ->all();

            return [
                'type'             => $profile->profileable_type,
                'typeLabel'        => self::PROFILE_TYPE_LABELS[$profile->profileable_type] ?? $profile->profileable_type,
                'entityName'       => $entityName,
                'roleDisplayNames' => $roleNames,
            ];
        })->all();
    }

    private function resolveEntityName(string $type, int $id): ?string
    {
        if ($type === 'dairy_workers') {
            $entity = DB::table('dairy_workers')
                ->join('core_entities', 'core_entities.id', '=', 'dairy_workers.entity_id')
                ->where('dairy_workers.person_id', $id)
                ->select('core_entities.entityable_type', 'core_entities.entityable_id')
                ->first();
            return $entity
                ? $this->resolveEntityName($entity->entityable_type, (int) $entity->entityable_id)
                : null;
        }

        return match ($type) {
            'dairy_plants'    => DB::table('dairy_plants')->where('id', $id)->value('name'),
            'dairy_suppliers' => DB::table('dairy_suppliers')->where('id', $id)->value('name'),
            default           => null,
        };
    }

    public function selectAsyncItems($search, $value = null)
    {
        $selected = null;
        $limit = 25;

        $query = Person::select(
            'core_persons.id',
            'core_persons.document_type',
            'core_persons.document_number',
            'core_persons.name',
            'core_persons.paternal_surname',
            'core_persons.maternal_surname',
            'core_persons.cellphone',
            'core_persons.email',
        );

        if (!empty($value)) {
            $selected = (clone $query)->where('core_persons.id', $value)->first();
            if ($selected) {
                $limit = 24;
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        if ($selected) {
            $query->where('core_persons.id', '!=', $value);
        }

        $items = $query->limit($limit)->get();

        if ($selected) {
            $items->prepend($selected);
        }

        return $items;
    }
}
