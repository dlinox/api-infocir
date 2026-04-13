<?php

namespace App\Models\Behavior;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Common\Traits\HasDataTable;

class Role extends Model
{

    use HasDataTable;

    protected $table = 'behavior_roles';

    protected $fillable = [
        'name',
        'display_name',
        'redirect_to',
        'level',
        'scope',
        'is_active',
    ];

    protected $casts = [
        'level'     => 'integer',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'display_name',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'behavior_role_permissions',
            'role_id',
            'permission_id'
        )->withTimestamps();
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(BehaviorProfile::class, 'role_id');
    }
}
