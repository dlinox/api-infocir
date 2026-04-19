<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasEntity;
use App\Models\Core\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    use HasDataTable, HasEntity;

    protected $table = 'dairy_plants';

    protected $fillable = [
        'ruc',
        'name',
        'trade_name',
        'type',
        'brand',
        'city',
        'address',
        'cellphone',
        'email',
        'latitude',
        'longitude',
        'capacity_liters',
        'product_quality',
        'has_sanitary_registration',
        'has_technification',
        'has_production_parameters',
        'has_digesa_parameters',
        'has_tdd_training',
        'description',
        'is_active',
        'company_type_id',
        'training_level_id',
        'institution_type_id',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'capacity_liters' => 'decimal:2',
        'is_active' => 'boolean',
        'has_sanitary_registration' => 'boolean',
        'has_technification' => 'boolean',
        'has_production_parameters' => 'boolean',
        'has_digesa_parameters' => 'boolean',
        'has_tdd_training' => 'boolean',
    ];

    public static $searchColumns = [
        'ruc',
        'name',
        'trade_name',
        'cellphone',
        'email',
    ];

    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class);
    }

    public function trainingLevel(): BelongsTo
    {
        return $this->belongsTo(TrainingLevel::class);
    }

    public function institutionType(): BelongsTo
    {
        return $this->belongsTo(InstitutionType::class);
    }

    public function cityRelation(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'code');
    }

    public function plantProducts(): HasMany
    {
        return $this->hasMany(PlantProduct::class);
    }
}
