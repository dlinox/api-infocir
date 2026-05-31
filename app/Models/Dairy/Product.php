<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasSlug;
use App\Models\Auth\User;
use App\Models\Core\UnitMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasDataTable, HasSlug;

    protected $table = 'dairy_products';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'product_type_id',
        'created_by',
        'unit_measure_id',
        'is_active',
        'contains_milk',
        'milk_liters_per_unit',
    ];

    protected $casts = [
        'is_active'            => 'boolean',
        'contains_milk'        => 'boolean',
        'milk_liters_per_unit' => 'decimal:3',
    ];

    public static $searchColumns = [
        'name',
        'description',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function unitMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitMeasure::class);
    }

    public function plantProducts(): HasMany
    {
        return $this->hasMany(PlantProduct::class);
    }

    public function presentations(): HasManyThrough
    {
        return $this->hasManyThrough(ProductPresentation::class, PlantProduct::class);
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProductGallery::class);
    }
}
