<?php

namespace App\Modules\Auth\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignInResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'token' => $this->token,
            'user' => $this->user,
        ];
    }
}
