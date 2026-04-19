<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasDataTable;

    protected $table = 'learning_lessons';

    protected $fillable = [
        'module_id',
        'title',
        'description',
        'order',
        'has_quiz',
        'passing_score',
        'is_active',
    ];

    protected $casts = [
        'module_id'     => 'integer',
        'order'         => 'integer',
        'has_quiz'      => 'boolean',
        'passing_score' => 'float',
        'is_active'     => 'boolean',
    ];

    public static $searchColumns = [
        'title',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(LessonResource::class, 'lesson_id')->orderBy('order');
    }

    public function quizQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class, 'lesson_id')->orderBy('order');
    }
}
