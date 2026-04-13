<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasEntity;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasDataTable, HasEntity;

    protected $table = 'dairy_suppliers';

    public array $searchColumns = [
        'dairy_suppliers.name',
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
        'description',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

}

