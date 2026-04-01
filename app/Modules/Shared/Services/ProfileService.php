<?php

namespace App\Modules\Shared\Services;

use Illuminate\Support\Facades\Hash;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;
use App\Models\Behavior\BehaviorProfile;
use App\Models\Core\Person;

class ProfileService
{
    public function getMe(): array
    {
        $user    = JWTAuth::user();
        $profile = $this->resolveProfile($user);

        $profile->load('coreProfile.person');

        $person = $profile->coreProfile?->person;

        if (!$person) throw new ApiException('Persona no encontrada', 404);

        return [
            'user'    => $user,
            'person'  => $person,
            'profile' => $profile,
        ];
    }

    public function updatePersonal(array $data): void
    {
        $user    = JWTAuth::user();
        $profile = $this->resolveProfile($user);

        $profile->load('coreProfile.person');

        $person = $profile->coreProfile?->person;

        if (!$person) throw new ApiException('Persona no encontrada', 404);

        if (!empty($data['cellphone'])) {
            $exists = Person::where('cellphone', $data['cellphone'])->where('id', '!=', $person->id)->exists();
            if ($exists) throw new ApiException('El numero de celular ya esta registrado');
        }

        if (!empty($data['email'])) {
            $exists = Person::where('email', $data['email'])->where('id', '!=', $person->id)->exists();
            if ($exists) throw new ApiException('El correo personal ya esta registrado');
        }

        $person->update($data);
    }

    public function changePassword(string $currentPassword, string $newPassword): void
    {
        $user = JWTAuth::user();

        $fresh = User::find($user->id);

        if (!Hash::check($currentPassword, $fresh->password)) {
            throw new ApiException('La contrasena actual es incorrecta');
        }

        $fresh->update(['password' => $newPassword]);
    }

    private function resolveProfile(User $user): BehaviorProfile
    {
        $payload   = JWTAuth::parseToken()->getPayload();
        $profileId = $payload->get('prf');

        $profile = BehaviorProfile::where('id', $profileId)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->first();

        if (!$profile) throw new ApiException('Perfil no encontrado', 404);

        return $profile;
    }
}
