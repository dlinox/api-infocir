<?php

namespace App\Models\Dairy;

use App\Models\Auth\User;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class StockMovement extends Model
{
    use HasDataTable;

    protected $table = 'dairy_stock_movements';

    public array $searchColumns = [
        'dairy_stock_movements.batch_code',
        'dairy_stock_movements.reason',
    ];

    protected $fillable = [
        'presentation_id',
        'plant_id',
        'type',
        'quantity',
        'batch_code',
        'expiration_date',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'expiration_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(fn (StockMovement $m) => $m->created_by = Auth::id());
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
