<?php

namespace App\Models\Behavior;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'behavior_permissions';

    protected $fillable = [
        'name',
        'display_name',
        'type',
        'parent_id',
        'level',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'behavior_role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id')->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }
}
