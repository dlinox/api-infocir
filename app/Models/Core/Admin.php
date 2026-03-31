<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Admin extends Model
{
    protected $table = 'core_admins';
    protected $primaryKey = 'person_id';
    public $incrementing = false;

    protected $fillable = [
        'person_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function coreProfile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }
}
