<?php

namespace App\Models\Learning;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProgramDelivery extends Model
{
    protected $table = 'learning_program_deliveries';

    protected $fillable = [
        'program_id',
        'instructor_id',
        'training_type_id',
        'start_date',
        'end_date',
        'status',
        'modality',
        'location',
        'max_participants',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'program_id'      => 'integer',
        'instructor_id'   => 'integer',
        'training_type_id'=> 'integer',
        'is_active'       => 'boolean',
        'created_by'      => 'integer',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(TrainingType::class, 'training_type_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments(): MorphMany
    {
        return $this->morphMany(Enrollment::class, 'enrollable');
    }
}
