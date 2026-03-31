<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Behavior\BehaviorProfile;

class Session extends Model
{
    protected $table = 'auth_sessions';

    protected $fillable = [
        'user_id',
        'profile_id',
        'session_token',
        'ip_address',
        'user_agent',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(BehaviorProfile::class, 'profile_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
