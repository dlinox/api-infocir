<?php

namespace App\Modules\Admin\Security\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFormResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'email'    => $this->email,
            'isActive' => $this->is_active,
            'personId' => $this->person?->id,
            'person'   => $this->person ? [
                'id'             => $this->person->id,
                'fullName'       => $this->person->full_name,
                'documentNumber' => $this->person->document_number,
            ] : null,
            'profiles' => $this->profiles->map(fn ($profile) => [
                'id'            => $profile->id,
                'roleId'        => $profile->role_id,
                'coreProfileId' => $profile->core_profile_id,
                'isActive'      => $profile->is_active,
            ]),
        ];
    }
}
