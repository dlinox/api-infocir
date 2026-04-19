<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPayment extends Model
{
    use HasDataTable;

    protected $table = 'dairy_supplier_payments';

    protected $fillable = [
        'plant_id',
        'supplier_id',
        'period_start',
        'period_end',
        'total_liters',
        'total_amount',
        'deductions',
        'net_amount',
        'status',
        'paid_at',
        'observations',
        'created_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_liters' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
