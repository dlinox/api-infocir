<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\City;
use App\Models\Core\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plant extends Model
{
    use HasDataTable;

    protected $table = 'dairy_plants';

    protected $fillable = [
        'ruc',
        'name',
        'trade_name',
        'type',
        'brand',
        'country',
        'city',
        'address',
        'cellphone',
        'email',
        'latitude',
        'longitude',
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

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'code');
    }
}
