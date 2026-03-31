<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class InstitutionType extends Model
{
    use HasDataTable;

    protected $table = 'dairy_institution_types';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'nature',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];
}
