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
        'base_unit_id',
        'conversion_factor',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active'         => 'boolean',
        'is_system'         => 'boolean',
        'conversion_factor' => 'float',
    ];

    public static array $searchColumns = [
        'name',
        'abbreviation',
    ];

    public function baseUnit()
    {
        return $this->belongsTo(UnitMeasure::class, 'base_unit_id');
    }
}
