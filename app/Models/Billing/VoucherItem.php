<?php

namespace App\Models\Billing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherItem extends Model
{
    protected $table = 'billing_voucher_items';

    protected $fillable = [
        'voucher_id',
        'item_order',
        'product_code',
        'product_code_sunat',
        'description',
        'unit',
        'quantity',
        'unit_value',
        'unit_price',
        'discount',
        'mto_base_igv',
        'percentage_igv',
        'igv',
        'tip_afe_igv',
        'total_impuestos',
        'valor_venta',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_value' => 'decimal:4',
        'unit_price' => 'decimal:4',
        'discount' => 'decimal:2',
        'mto_base_igv' => 'decimal:2',
        'percentage_igv' => 'decimal:2',
        'igv' => 'decimal:2',
        'total_impuestos' => 'decimal:2',
        'valor_venta' => 'decimal:2',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }
}
