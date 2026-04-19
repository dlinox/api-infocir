<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

class Training extends Model
{
    use HasDataTable;

    protected $table = 'learning_trainings';

    public array $searchColumns = [
        'learning_courses.name',
        'learning_trainings.location',
    ];

    protected $fillable = [
        'course_id',
        'instructor_id',
        'training_type_id',
        'certificate_template_id',
        'is_event_only',
        'start_date',
        'end_date',
        'status',
        'modality',
        'location',
        'latitude',
        'longitude',
        'meeting_url',
        'max_participants',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'course_id'               => 'integer',
        'instructor_id'           => 'integer',
        'training_type_id'        => 'integer',
        'certificate_template_id' => 'integer',
        'is_event_only'           => 'boolean',
        'latitude'                => 'decimal:7',
        'longitude'               => 'decimal:7',
        'is_active'               => 'boolean',
        'created_by'              => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Training $training) => $training->created_by = Auth::id());
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function trainingType(): BelongsTo
    {
        return $this->belongsTo(TrainingType::class, 'training_type_id');
    }

    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
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
