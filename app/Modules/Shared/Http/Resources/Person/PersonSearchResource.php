<?php

namespace App\Modules\Shared\Http\Resources\Person;

use App\Common\Helpers\Mask;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonSearchResource extends JsonResource
{
    public function toArray($request)
    {
        $person = $this->resource['person'];
        $user   = $this->resource['user'] ?? null;

        return [
            'exists'               => $this->resource['exists'] ?? true,
            'profileAlreadyExists' => (bool) ($this->resource['profileAlreadyExists'] ?? false),
            'person' => [
                'id'                    => $person->id,
                'documentType'          => $person->document_type,
                'documentNumber'        => $person->document_number,
                'nameMasked'            => Mask::name($person->name),
                'paternalSurnameMasked' => Mask::name($person->paternal_surname),
                'maternalSurnameMasked' => Mask::name($person->maternal_surname),
                'emailMasked'           => Mask::email($person->email),
                'cellphoneMasked'       => Mask::phone($person->cellphone),
            ],
            'user' => $user ? [
                'id'           => $user->id,
                'usernameMasked' => Mask::username($user->username),
                'emailMasked'  => Mask::email($user->email),
                'isActive'     => (bool) $user->is_active,
            ] : null,
            'profiles' => array_map(fn ($p) => [
                'type'             => $p['type'],
                'typeLabel'        => $p['typeLabel'],
                'entityName'       => $p['entityName'],
                'roleDisplayNames' => $p['roleDisplayNames'],
            ], $this->resource['profiles'] ?? []),
        ];
    }
}
