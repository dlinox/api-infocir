<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessPlan extends Model
{
    protected $table = 'dairy_business_plans';

    protected $fillable = [
        'plant_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
