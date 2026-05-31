<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionRouteExpense extends Model
{
    protected $table = 'dairy_collection_route_expenses';

    protected $fillable = [
        'collection_route_id',
        'working_capital_catalog_id',
        'amount',
        'quantity',
        'notes',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'quantity' => 'decimal:2',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(CollectionRoute::class, 'collection_route_id');
    }

    public function catalogItem(): BelongsTo
    {
        return $this->belongsTo(WorkingCapitalCatalog::class, 'working_capital_catalog_id');
    }
}
