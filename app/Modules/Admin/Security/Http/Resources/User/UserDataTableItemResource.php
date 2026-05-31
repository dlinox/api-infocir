<?php

namespace App\Modules\Admin\Security\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDataTableItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fullName = trim(implode(' ', array_filter([
            $this->person_name,
            $this->person_paternal_surname,
            $this->person_maternal_surname,
        ])));

        return [
            'id'           => $this->id,
            'username'     => $this->username,
            'email'        => $this->email,
            'isActive'     => $this->is_active,
            'lastSignInAt' => $this->last_sign_in_at,
            'person'       => $this->person_name ? [
                'fullName'       => $fullName,
                'documentNumber' => $this->person_document_number,
            ] : null,
            'profiles'     => $this->profiles->map(fn ($profile) => [
                'id'       => $profile->id,
                'isActive' => $profile->is_active,
                'role'     => $profile->role ? [
                    'id'          => $profile->role->id,
                    'displayName' => $profile->role->display_name,
                    'scope'       => $profile->role->scope,
                ] : null,
            ]),
        ];
    }
}
