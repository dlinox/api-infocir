<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $table = 'learning_lesson_progress';

    protected $fillable = [
        'enrollment_id',
        'lesson_id',
        'completed',
        'completed_at',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
        'lesson_id'     => 'integer',
        'completed'     => 'boolean',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}
