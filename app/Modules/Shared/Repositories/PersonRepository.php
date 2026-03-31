<?php

namespace App\Modules\Shared\Repositories;

use App\Common\Exceptions\ApiException;
use App\Models\Core\Person;
use Illuminate\Support\Facades\DB;

class PersonRepository
{
    private const PROFILE_TABLES = [
        'admins'   => ['table' => 'profile_admins',   'column' => 'core_person_id'],
        'teachers' => ['table' => 'profile_teachers', 'column' => 'core_person_id'],
        'students' => ['table' => 'profile_students', 'column' => 'core_person_id'],
        'clients'  => ['table' => 'profile_clients',  'column' => 'id'],
        'workers'  => ['table' => 'profile_workers',  'column' => 'id'],
        'barbers'  => ['table' => 'profile_barbers',  'column' => 'id'],
    ];

    public function searchByDocument(string $documentType, string $documentNumber, string $profile, ?int $id = null): ?array
    {
        $profileConfig = self::PROFILE_TABLES[$profile] ?? null;
        if (!$profileConfig) return null;

        $person = Person::where('document_type', $documentType)
            ->where('document_number', $documentNumber)
            ->first();

        // Con ID (edición)
        if ($id) {
            if (!$person) return null;
            if ($person->id === $id) return null;
            throw new ApiException('El documento pertenece a otra persona');
        }

        // Sin ID (creación)
        if (!$person) return null;

        $hasProfile = DB::table($profileConfig['table'])
            ->where($profileConfig['column'], $person->id)
            ->exists();

        if ($hasProfile) {
            throw new ApiException("La persona ya tiene el perfil de {$profile}");
        }

        // Persona existe pero no tiene este perfil → devolver con sus perfiles existentes
        $existingProfiles = [];
        foreach (self::PROFILE_TABLES as $type => $config) {
            if (DB::table($config['table'])->where($config['column'], $person->id)->exists()) {
                $existingProfiles[] = $type;
            }
        }

        // Buscar usuario asociado (una persona solo puede tener una cuenta)
        $user = DB::table('behavior_profiles')
            ->join('auth_users', 'auth_users.id', '=', 'behavior_profiles.user_id')
            ->join('core_profiles', 'core_profiles.id', '=', 'behavior_profiles.core_profile_id')
            ->where('core_profiles.person_id', $person->id)
            ->select('auth_users.id', 'auth_users.username', 'auth_users.email', 'auth_users.is_active')
            ->first();

        return [
            'person' => $person,
            'profiles' => $existingProfiles,
            'user' => $user,
        ];
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
            'core_persons.phone',
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
