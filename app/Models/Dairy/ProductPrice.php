<?php

namespace App\Models\Dairy;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class ProductPrice extends Model
{
    protected $table = 'dairy_product_prices';

    protected $fillable = [
        'presentation_id',
        'price',
        'cost',
        'effective_from',
        'effective_until',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'effective_from' => 'date',
        'effective_until' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(fn (ProductPrice $price) => $price->created_by = Auth::id());
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
