<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MilkQualityTest extends Model
{
    protected $table = 'dairy_milk_quality_tests';

    protected $fillable = [
        'milk_collection_id',
        'fat_percentage',
        'snf_percentage',
        'density',
        'acidity',
        'temperature',
        'quality_grade',
        'tested_by',
        'tested_at',
        'observations',
    ];

    protected $casts = [
        'fat_percentage' => 'decimal:2',
        'snf_percentage' => 'decimal:2',
        'density' => 'decimal:4',
        'acidity' => 'decimal:2',
        'temperature' => 'decimal:1',
        'tested_at' => 'datetime',
    ];

    public function milkCollection(): BelongsTo
    {
        return $this->belongsTo(MilkCollection::class);
    }
}
