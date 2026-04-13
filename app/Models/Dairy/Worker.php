<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\Entity;
use App\Models\Core\InstructionDegree;
use App\Models\Core\Person;
use App\Models\Core\Profession;
use App\Models\Core\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Worker extends Model
{
    use HasDataTable;

    public const ROLE_NAME = 'worker';

    protected $table = 'dairy_workers';

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
        'entity_id',
        'position_id',
        'instruction_degree_id',
        'profession_id',
        'is_active',
    ];

    protected $casts = [
        'person_id'            => 'integer',
        'entity_id'            => 'integer',
        'position_id'          => 'integer',
        'instruction_degree_id' => 'integer',
        'profession_id'        => 'integer',
        'is_active'            => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
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

