<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierCattleBreed extends Model
{
    use HasDataTable;

    protected $table = 'dairy_supplier_cattle_breeds';

    public array $searchColumns = [
        'dairy_supplier_cattle_breeds.breed_name',
        'dairy_supplier_cattle_breeds.notes',
    ];

    protected $fillable = [
        'supplier_id',
        'breed_name',
        'count',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
