<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class InvestmentCategory extends Model
{
    use HasDataTable;

    protected $table = 'dairy_investment_categories';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'group',
        'default_useful_life_years',
        'default_validity_years',
        'hint',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'                 => 'boolean',
        'default_useful_life_years' => 'integer',
        'default_validity_years'    => 'integer',
    ];

    public static array $searchColumns = [
        'name',
    ];
}
