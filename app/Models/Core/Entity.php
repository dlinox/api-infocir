<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use App\Models\Billing\Series;
use App\Models\Billing\Voucher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Entity extends Model
{
    use HasDataTable;

    protected $table = 'core_entities';

    protected $fillable = [
        'entityable_type',
        'entityable_id',
        'entity_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $searchColumns = [
        'entityable_type',
    ];

    public function entityable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class, 'issuer_entity_id');
    }

    public function billingSeries(): HasMany
    {
        return $this->hasMany(Series::class, 'issuer_entity_id');
    }
}
