<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $table = 'core_cities';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'department',
        'province',
        'district',
        'country',
    ];

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }
}
