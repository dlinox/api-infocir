<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasDataTable;

    protected $table = 'learning_quiz_questions';

    protected $fillable = [
        'lesson_id',
        'question',
        'hint',
        'order',
    ];

    protected $casts = [
        'lesson_id' => 'integer',
        'order'     => 'integer',
    ];

    public static $searchColumns = [
        'question',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(QuizOption::class, 'question_id')->orderBy('order');
    }
}
