<?php

namespace App\Modules\Shared\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Auth\User;
use App\Models\Core\Person;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class CreateOrUpdatePersonAction
{
    public function __construct(
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
    ) {}

    public function execute(array $data, bool $ensureUser = true): Person
    {
        $person = !empty($data['id'])
            ? $this->updatePerson($data)
            : $this->createPerson($data);

        if ($ensureUser && !$person->user_id) {
            $user = $this->createOrUpdateUserAction->execute([
                'username'  => $person->document_number,
                'password'  => $person->document_number,
                'email'     => $person->email,
                'is_active' => true,
            ]);
            $person->update(['user_id' => $user->id]);
            $person->refresh();
        }

        return $person;
    }

    private function createPerson(array $data): Person
    {
        self::validate($data, null);
        return Person::create($data);
    }

    private function updatePerson(array $data): Person
    {
        $person = Person::find($data['id']);
        if (!$person) throw new ApiException('La persona no existe');

        self::validate($data, $person->id);
        $person->update($data);

        if ($person->user_id) {
            $this->createOrUpdateUserAction->execute([
                'id'       => $person->user_id,
                'username' => $person->document_number,
                'email'    => $person->email,
            ]);
        }

        return $person;
    }

    private static function validate(array $data, ?int $id = null): void
    {
        $documentExists = Person::where('document_type', $data['document_type'])
            ->where('document_number', $data['document_number'])
            ->where('id', '!=', $id)
            ->exists();
        if ($documentExists) throw new ApiException('El documento ya fue registrado.');

        if (!empty($data['email'])) {
            $emailExists = Person::where('email', $data['email'])->where('id', '!=', $id)->exists();
            if ($emailExists) throw new ApiException('El correo personal ya fue registrado.');
        }

        if (!empty($data['cellphone'])) {
            $phoneExists = Person::where('cellphone', $data['cellphone'])->where('id', '!=', $id)->exists();
            if ($phoneExists) throw new ApiException('El número de celular ya fue registrado.');
        }
    }
}
