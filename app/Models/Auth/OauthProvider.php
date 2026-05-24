<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;

class OauthProvider extends Model
{
    protected $table = 'auth_oauth_providers';

    protected $fillable = [
        'provider',
        'client_id',
        'client_secret',
        'redirect',
        'is_active',
    ];

    protected $hidden = [
        'client_secret',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
