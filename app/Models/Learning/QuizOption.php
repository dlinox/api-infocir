<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizOption extends Model
{
    use HasDataTable;

    protected $table = 'learning_quiz_options';

    protected $fillable = [
        'question_id',
        'text',
        'is_correct',
        'explanation',
        'order',
    ];

    protected $casts = [
        'question_id' => 'integer',
        'is_correct'  => 'boolean',
        'order'       => 'integer',
    ];

    public static $searchColumns = [
        'text',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
