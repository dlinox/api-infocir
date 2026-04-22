<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierMilkRegistration extends Model
{
    use HasDataTable;

    protected $table = 'dairy_supplier_milk_registrations';

    public array $searchColumns = [
        'dairy_supplier_milk_registrations.observations',
    ];

    protected $fillable = [
        'supplier_id',
        'registration_date',
        'shift',
        'quantity_liters',
        'number_of_cows',
        'observations',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'quantity_liters'   => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
