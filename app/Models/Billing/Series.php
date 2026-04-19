<?php

namespace App\Models\Billing;

use App\Common\Traits\HasDataTable;
use App\Models\Core\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Series extends Model
{
    use HasDataTable;

    protected $table = 'billing_series';

    protected $fillable = [
        'issuer_entity_id',
        'voucher_type',
        'serie',
        'current_correlative',
        'is_active',
    ];

    protected $casts = [
        'current_correlative' => 'integer',
        'is_active' => 'boolean',
    ];

    public function issuerEntity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'issuer_entity_id');
    }
}
