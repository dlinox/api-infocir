<?php

namespace App\Models\Learning;

use App\Models\Dairy\Worker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Enrollment extends Model
{
    protected $table = 'learning_enrollments';

    protected $fillable = [
        'enrollable_type',
        'enrollable_id',
        'worker_id',
        'status',
        'progress',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrollable_id' => 'integer',
        'worker_id'     => 'integer',
        'progress'      => 'float',
    ];

    public function enrollable(): MorphTo
    {
        return $this->morphTo();
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'person_id');
    }

    public function lessonProgress(): HasMany
    {
        return $this->hasMany(LessonProgress::class, 'enrollment_id');
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'enrollment_id');
    }

    public function certification(): HasOne
    {
        return $this->hasOne(Certification::class, 'enrollment_id');
    }
}
