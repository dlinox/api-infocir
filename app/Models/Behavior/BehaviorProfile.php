<?php

namespace App\Models\Behavior;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Auth\User;
use App\Models\Core\Profile as CoreProfile;

class BehaviorProfile extends Model
{
    protected $table = 'behavior_profiles';

    protected $fillable = [
        'user_id',
        'role_id',
        'core_profile_id',
        'is_active',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'role_id' => 'integer',
        'core_profile_id' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function coreProfile(): BelongsTo
    {
        return $this->belongsTo(CoreProfile::class, 'core_profile_id');
    }
}
