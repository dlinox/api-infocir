<?php

namespace App\Models\Dairy;

use App\Models\Core\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentPlan extends Model
{
    protected $table = 'dairy_investment_plans';

    protected $fillable = [
        'entity_id',
        'name',
        'period_year',
        'status',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'period_year'  => 'integer',
        'total_amount' => 'decimal:2',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvestmentItem::class, 'plan_id');
    }
}
