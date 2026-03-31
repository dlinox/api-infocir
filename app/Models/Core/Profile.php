<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Profile extends Model
{
    protected $table = 'core_profiles';

    protected $fillable = [
        'person_id',
        'profileable_type',
        'profileable_id',
    ];

    protected $casts = [
        'person_id' => 'integer',
        'profileable_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function profileable(): MorphTo
    {
        return $this->morphTo();
    }
}
