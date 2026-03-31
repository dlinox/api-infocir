<?php

namespace App\Modules\Auth\Repositories\Actions;

use App\Models\Auth\User;
use App\Common\Exceptions\ApiException;

class CreateOrUpdateUserAction
{
    public function execute(array $data): User
    {
        $id = $data['id'] ?? null;

        if ($id) {
            $user = User::where('id', $id)->first();
            self::validate($data, $user->id);
            if (empty($data['password'])) {
                unset($data['password']);
            }
            $user->update($data);
            return $user;
        }

        self::validate($data, null);
        return User::create($data);
    }

    private function validate(array $data, ?int $id = null): void
    {
        $usernameExists = User::where('username', $data['username'])
            ->where('id', '!=', $id)
            ->exists();
        if ($usernameExists) throw new ApiException('El nombre de usuario ya existe');

        if (!empty($data['email'])) {
            $emailExists = User::where('email', $data['email'])->where('id', '!=', $id)->exists();
            if ($emailExists) throw new ApiException('El correo del usuario ya existe');
        }
    }
}
