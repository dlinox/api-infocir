<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $table = 'learning_quiz_attempts';

    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'score',
        'passed',
        'attempted_at',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
        'lesson_id'     => 'integer',
        'score'         => 'float',
        'passed'        => 'boolean',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }
}
