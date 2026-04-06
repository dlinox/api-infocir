<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasDataTable;

    protected $table = 'dairy_products';

    protected $fillable = [
        'name',
        'description',
        'product_type_id',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function plantProducts(): HasMany
    {
        return $this->hasMany(PlantProduct::class);
    }

    public function presentations(): HasManyThrough
    {
        return $this->hasManyThrough(ProductPresentation::class, PlantProduct::class);
    }
}
