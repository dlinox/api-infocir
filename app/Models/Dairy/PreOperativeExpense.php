<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOperativeExpense extends Model
{
    use HasDataTable;

    protected $table = 'dairy_pre_operative_expenses';

    protected $fillable = [
        'entity_id',
        'investment_category_id',
        'name',
        'payment_date',
        'amount',
        'recurrence_type',
        'validity_years',
        'expiration_date',
        'notes',
    ];

    protected $casts = [
        'payment_date'    => 'date',
        'expiration_date' => 'date',
        'amount'          => 'decimal:2',
        'validity_years'  => 'integer',
    ];

    public static $searchColumns = [
        'dairy_pre_operative_expenses.name',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }
}
