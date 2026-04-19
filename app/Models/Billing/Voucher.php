<?php

namespace App\Models\Billing;

use App\Common\Traits\HasDataTable;
use App\Models\Core\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Voucher extends Model
{
    use HasDataTable;

    protected $table = 'billing_vouchers';

    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'issue_date',
        'due_date',
        'currency',
        'operation_type',
        'issuer_entity_id',
        'issuer_ruc',
        'issuer_name',
        'issuer_address',
        'client_document_type',
        'client_document_number',
        'client_name',
        'client_address',
        'mto_oper_gravadas',
        'mto_oper_inafectas',
        'mto_oper_exoneradas',
        'mto_oper_gratuitas',
        'mto_igv',
        'mto_isc',
        'mto_otros_tributos',
        'icbper',
        'total_impuestos',
        'valor_venta',
        'sub_total',
        'total',
        'redondeo',
        'descuento_global',
        'affected_voucher_type',
        'affected_voucher_number',
        'note_reason_code',
        'note_reason_description',
        'xml_path',
        'cdr_path',
        'hash',
        'sunat_status',
        'sunat_code',
        'sunat_description',
        'sunat_notes',
        'sent_at',
        'voucherable_type',
        'voucherable_id',
        'status',
        'observations',
        'legend_text',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'mto_oper_gravadas' => 'decimal:2',
        'mto_oper_inafectas' => 'decimal:2',
        'mto_oper_exoneradas' => 'decimal:2',
        'mto_oper_gratuitas' => 'decimal:2',
        'mto_igv' => 'decimal:2',
        'mto_isc' => 'decimal:2',
        'mto_otros_tributos' => 'decimal:2',
        'icbper' => 'decimal:2',
        'total_impuestos' => 'decimal:2',
        'valor_venta' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'total' => 'decimal:2',
        'redondeo' => 'decimal:2',
        'descuento_global' => 'decimal:2',
        'sunat_notes' => 'array',
        'sent_at' => 'datetime',
    ];

    public function issuerEntity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'issuer_entity_id');
    }

    public function voucherable(): MorphTo
    {
        return $this->morphTo();
    }

    public function items(): HasMany
    {
        return $this->hasMany(VoucherItem::class);
    }
}
