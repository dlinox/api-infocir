<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductionBatch extends Model
{
    use HasDataTable;

    protected $table = 'dairy_production_batches';

    public array $searchColumns = [
        'dairy_production_batches.batch_code',
        'dairy_production_batches.observations',
    ];

    protected $fillable = [
        'plant_id',
        'batch_code',
        'production_date',
        'quantity_units',
        'status',
        'presentation_id',
        'maturation_start_date',
        'maturation_end_date',
        'observations',
        'rejection_type',
        'ingredients_consumed',
        'created_by',
    ];

    protected $casts = [
        'production_date' => 'date',
        'quantity_units' => 'integer',
        'maturation_start_date' => 'date',
        'maturation_end_date' => 'date',
        'ingredients_consumed' => 'boolean',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'dairy_batch_suppliers', 'batch_id', 'supplier_id')
            ->withPivot('quantity_liters');
    }
}
