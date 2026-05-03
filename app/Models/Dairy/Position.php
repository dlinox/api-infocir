<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Behavior\Role;
use App\Models\Dairy\InvestmentCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Position extends Model
{
    use HasDataTable;

    protected $table = 'dairy_positions';

    protected $fillable = [
        'name',
        'description',
        'entity_type',
        'role_id',
        'investment_category_id',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'entity_type' => 'array',
    ];

    public static array $searchColumns = [
        'name',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function investmentCategory(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class);
    }
}
