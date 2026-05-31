<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\CoreFile;
use App\Models\Core\UnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkingCapitalCatalog extends Model
{
    use HasDataTable;

    protected $table = 'dairy_working_capital_catalog';
    public $timestamps = false;

    protected $fillable = [
        'investment_category_id',
        'unit_measure_id',
        'icon_file_id',
        'name',
        'description',
        'color',
        'recurrence_type',
        'recurrence_every_days',
        'is_route_expense',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_route_expense' => 'boolean',
        'recurrence_every_days' => 'integer',
    ];

    public static array $searchColumns = [
        'name',
    ];

    public function investmentCategory(): BelongsTo
    {
        return $this->belongsTo(InvestmentCategory::class, 'investment_category_id');
    }

    public function unitMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_measure_id');
    }

    public function iconFile(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'icon_file_id');
    }
}
