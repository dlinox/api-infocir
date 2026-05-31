<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionRoute extends Model
{
    use HasDataTable;

    protected $table = 'dairy_collection_routes';

    protected $fillable = [
        'plant_id',
        'collector_id',
        'started_at',
        'ended_at',
        'start_latitude',
        'start_longitude',
        'end_latitude',
        'end_longitude',
        'initial_mileage',
        'final_mileage',
        'status',
        'observations',
    ];

    protected $casts = [
        'started_at'       => 'datetime',
        'ended_at'         => 'datetime',
        'start_latitude'   => 'decimal:7',
        'start_longitude'  => 'decimal:7',
        'end_latitude'     => 'decimal:7',
        'end_longitude'    => 'decimal:7',
        'initial_mileage'  => 'decimal:2',
        'final_mileage'    => 'decimal:2',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

    public function milkCollections(): HasMany
    {
        return $this->hasMany(MilkCollection::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(CollectionRouteExpense::class, 'collection_route_id');
    }
}
