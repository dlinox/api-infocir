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
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];
}
