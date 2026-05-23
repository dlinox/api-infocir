<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class UnitMeasure extends Model
{
    use HasDataTable;

    protected $table = 'core_unit_measures';

    protected $fillable = [
        'name',
        'abbreviation',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
        'abbreviation',
    ];
}
