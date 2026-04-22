<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetCatalog extends Model
{
    use HasDataTable;

    protected $table = 'dairy_asset_catalog';
    public $timestamps = false;

    protected $fillable = [
        'investment_category_id',
        'name',
        'brand',
        'model',
        'useful_life_years',
        'depreciation_method',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
        'brand',
    ];

    public function investmentCategory(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }
}
