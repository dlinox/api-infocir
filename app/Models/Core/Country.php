<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'core_countries';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];
}
