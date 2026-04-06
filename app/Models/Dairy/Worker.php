<?php

namespace App\Models\Dairy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Common\Traits\HasDataTable;
use App\Models\Core\InstructionDegree;
use App\Models\Core\Person;
use App\Models\Core\Profession;
use App\Models\Core\Profile;

class Worker extends Model
{
    use HasDataTable;

    protected $table = 'dairy_plant_workers';

    protected $primaryKey = 'person_id';

    public $incrementing = false;

    public array $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'core_persons.cellphone',
    ];

    protected $fillable = [
        'person_id',
        'plant_id',
        'position_id',
        'instruction_degree_id',
        'profession_id',
        'is_manager',
        'is_active',
    ];

    protected $casts = [
        'person_id' => 'integer',
        'plant_id' => 'integer',
        'position_id' => 'integer',
        'instruction_degree_id' => 'integer',
        'profession_id' => 'integer',
        'is_manager' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'plant_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function instructionDegree(): BelongsTo
    {
        return $this->belongsTo(InstructionDegree::class, 'instruction_degree_id');
    }

    public function profession(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    public function coreProfile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }
}
