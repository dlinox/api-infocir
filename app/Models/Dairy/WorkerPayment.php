<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerPayment extends Model
{
    use HasDataTable;

    protected $table = 'dairy_worker_payments';

    public array $searchColumns = [
        'dairy_worker_payments.observations',
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
    ];

    protected $fillable = [
        'plant_id',
        'worker_person_id',
        'period_year',
        'period_month',
        'base_salary',
        'bonuses',
        'deductions',
        'net_amount',
        'status',
        'paid_at',
        'paid_by',
        'created_by',
        'observations',
    ];

    protected $casts = [
        'plant_id' => 'integer',
        'worker_person_id' => 'integer',
        'period_year' => 'integer',
        'period_month' => 'integer',
        'base_salary' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'paid_by' => 'integer',
        'created_by' => 'integer',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_person_id', 'person_id');
    }
}
