<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasDataTable, HasEntity;

    protected $table = 'dairy_suppliers';

    public array $searchColumns = [
        // 'dairy_suppliers.name',
        'dairy_suppliers.trade_name',
        'dairy_suppliers.document_number',
        'dairy_suppliers.cellphone',
        'dairy_suppliers.email',
    ];

    protected $fillable = [
        'supplier_type',
        'document_type',
        'document_number',
        'name',
        'trade_name',
        'cellphone',
        'email',
        'address',
        'city',
        'latitude',
        'longitude',
        'community',
        'total_cows',
        'cows_in_production',
        'dry_cows',
        'tank_capacity_liters',
        'tank_alert_percentage',
        'reference_price_per_liter',
        'description',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'total_cows' => 'integer',
        'cows_in_production' => 'integer',
        'dry_cows' => 'integer',
        'tank_capacity_liters' => 'decimal:2',
        'tank_alert_percentage' => 'integer',
        'reference_price_per_liter' => 'decimal:4',
        'is_active' => 'boolean',
    ];

    public function galleries(): HasMany
    {
        return $this->hasMany(SupplierGallery::class);
    }
}

