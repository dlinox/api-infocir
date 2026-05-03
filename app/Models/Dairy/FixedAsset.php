<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FixedAsset extends Model
{
    use HasDataTable;

    protected $table = 'dairy_fixed_assets';

    protected $fillable = [
        'entity_id',
        'investment_category_id',
        'asset_catalog_id',
        'investment_item_id',
        'name',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'quantity',
        'residual_value',
        'useful_life_years',
        'depreciation_method',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchase_date'     => 'date',
        'purchase_cost'     => 'decimal:2',
        'residual_value'    => 'decimal:2',
        'quantity'          => 'integer',
        'useful_life_years' => 'integer',
    ];

    public static $searchColumns = [
        'dairy_fixed_assets.name',
        'dairy_fixed_assets.serial_number',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }
}
