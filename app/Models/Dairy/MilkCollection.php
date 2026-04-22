<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\CoreFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MilkCollection extends Model
{
    use HasDataTable;

    protected $table = 'dairy_milk_collections';

    public array $searchColumns = [
        'dairy_milk_collections.observations',
        'dairy_suppliers.name',
        'dairy_suppliers.trade_name',
        'dairy_suppliers.document_number',
    ];

    protected $fillable = [
        'plant_id',
        'supplier_id',
        'collection_date',
        'shift',
        'quantity_liters',
        'price_per_liter',
        'total_amount',
        'latitude',
        'longitude',
        'file_id',
        'payment_status',
        'observations',
        'created_by',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'quantity_liters' => 'decimal:2',
        'price_per_liter' => 'decimal:4',
        'total_amount' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'file_id');
    }

    public function qualityTest(): HasOne
    {
        return $this->hasOne(MilkQualityTest::class);
    }
}
