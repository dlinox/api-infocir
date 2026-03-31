<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'phone',
        'email',
        'gender',
        'address',
        'city',
        'country',
    ];

    protected $casts = [
        'date_birth' => 'date',
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

    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->paternal_surname} {$this->maternal_surname}");
    }
}
