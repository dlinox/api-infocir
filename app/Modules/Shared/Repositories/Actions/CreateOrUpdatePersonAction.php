<?php

namespace App\Modules\Shared\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Core\Person;

class CreateOrUpdatePersonAction
{
    public function __construct() {}

    public function execute(array $data, ?int $id = null): Person
    {

        if ($data['id']) {
            $person = Person::where('id', $data['id'])->first();

            // throw new ApiException('El id es :' . $person->id);

            if (!$person) throw new ApiException('La persona no existe');

            self::validate($data, $person->id);

            $person->update($data);

            return $person;
        }
        self::validate($data, null);
        return Person::create($data);
    }

    private static function validate(array $data, ?int $id = null): void
    {

        $documentExists = Person::where('document_type', $data['document_type'])
            ->where('document_number', $data['document_number'])
            ->where('id', '!=', $id)
            ->exists();
        if ($documentExists) throw new ApiException('El documento, ya fue registardo.');

        if (!empty($data['email'])) {
            $emailExists = Person::where('email', $data['email'])->where('id', '!=', $id)->exists();
            if ($emailExists) throw new ApiException('El correo personal, ya fue registardo.');
        }

        if (!empty($data['cellphone'])) {
            $phoneExists = Person::where('cellphone', $data['cellphone'])->where('id', '!=', $id)->exists();
            if ($phoneExists) throw new ApiException('El número de celular, ya fue registrado.');
        }
    }
}
