<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFormula extends Model
{
    protected $table = 'dairy_product_formulas';

    protected $fillable = [
        'presentation_id',
        'supply_id',
        'quantity',
        'unit_price',
        'version',
        'is_current',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_price' => 'decimal:3',
        'is_current' => 'boolean',
        'version' => 'integer',
    ];

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }

    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
    }
}
