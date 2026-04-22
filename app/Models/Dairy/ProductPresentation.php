<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\UnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductPresentation extends Model
{
    use HasDataTable;

    protected $table = 'dairy_product_presentations';

    protected $fillable = [
        'plant_product_id',
        'sku',
        'name',
        'unit_measure_id',
        'content',
        'barcode',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'content' => 'decimal:3',
    ];

    public static $searchColumns = [
        'sku',
        'name',
        'barcode',
    ];

    public function plantProduct(): BelongsTo
    {
        return $this->belongsTo(PlantProduct::class);
    }

    public function unitMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitMeasure::class);
    }

    public function formulas(): HasMany
    {
        return $this->hasMany(ProductFormula::class, 'presentation_id');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProductGallery::class, 'presentation_id');
    }
}
