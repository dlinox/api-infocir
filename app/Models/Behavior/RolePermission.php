<?php

namespace App\Models\Behavior;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'behavior_role_permissions';

    protected $fillable = [
        'role_id',
        'permission_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
