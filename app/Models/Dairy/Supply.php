<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use App\Models\Core\UnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Supply extends Model
{
    use HasDataTable;

    protected $table = 'dairy_supplies';

    protected $fillable = [
        'name',
        'unit_measure_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'unit_measure_id' => 'integer',
        'created_by' => 'integer',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Supply $supply) => $supply->created_by = Auth::id());
    }

    public function unitMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitMeasure::class, 'unit_measure_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
