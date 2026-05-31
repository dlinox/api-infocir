<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOperativeCatalog extends Model
{
    use HasDataTable;

    protected $table = 'dairy_pre_operative_catalog';
    public $timestamps = false;

    protected $fillable = [
        'investment_category_id',
        'name',
        'issuing_entity',
        'recurrence_type',
        'validity_years',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_public' => 'boolean',
    ];

    public static array $searchColumns = [
        'name',
        'issuing_entity',
    ];

    public function investmentCategory(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }
}
