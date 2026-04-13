<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use App\Models\Core\Person;
use App\Models\Core\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Instructor extends Model
{
    use HasDataTable;

    public const ROLE_NAME = 'instructor';

    protected $table = 'learning_instructors';

    public array $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'core_persons.cellphone',
    ];

    protected $fillable = [
        'person_id',
        'is_active',
    ];

    protected $casts = [
        'person_id' => 'integer',
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
