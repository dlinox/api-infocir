<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasDataTable;

    protected $table = 'dairy_orders';

    protected $fillable = [
        'code',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
        'customer_document',
        'address',
        'district',
        'city',
        'reference',
        'inquiry',
        'plant_id',
        'subtotal',
        'total',
        'whatsapp_sent_at',
        'stock_applied',
        'closed_at',
        'receipt_number',
        'receipt_issued_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'whatsapp_sent_at' => 'datetime',
        'stock_applied' => 'boolean',
        'closed_at' => 'datetime',
        'receipt_issued_at' => 'datetime',
    ];

    public static array $searchColumns = [
        'code',
        'customer_name',
        'customer_phone',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
}
