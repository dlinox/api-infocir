<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentItem extends Model
{
    protected $table = 'dairy_investment_items';

    protected $fillable = [
        'plan_id',
        'investment_category_id',
        'name',
        'unit_value',
        'quantity',
        'total',
        'sort_order',
    ];

    protected $casts = [
        'unit_value' => 'decimal:2',
        'quantity'   => 'decimal:4',
        'total'      => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(InvestmentPlan::class, 'plan_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }
}
