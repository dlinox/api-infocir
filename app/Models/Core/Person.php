<?php

namespace App\Models\Core;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    protected $table = 'core_persons';

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'paternal_surname',
        'maternal_surname',
        'date_birth',
        'cellphone',
        'email',
        'gender',
        'address',
        'city',
        'country',
        'user_id',
    ];

    protected $casts = [
        'date_birth' => 'date',
        'user_id' => 'integer',
    ];

    public function documentTypeRelation(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type', 'code');
    }

    public function genderRelation(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender', 'code');
    }

    public function cityRelation(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'code');
    }

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'person_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->paternal_surname} {$this->maternal_surname}");
    }
}
