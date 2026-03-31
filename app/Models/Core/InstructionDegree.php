<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class InstructionDegree extends Model
{
    use HasDataTable;

    protected $table = 'core_instruction_degrees';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];
}
