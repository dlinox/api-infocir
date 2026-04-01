<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Common\Traits\HasDataTable;
use App\Models\Core\Person;
use App\Models\Core\Profile;

class Supplier extends Model
{
    use HasDataTable;

    protected $table = 'dairy_suppliers';

    protected $primaryKey = 'person_id';

    public $incrementing = false;

    protected $fillable = [
        'person_id',
        'supplier_type',
        'trade_name',
        'cellphone',
        'email',
        'address',
        'country',
        'city',
        'latitude',
        'longitude',
        'description',
        'is_active',
    ];

    protected $casts = [
        'person_id' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function coreProfile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }
}
