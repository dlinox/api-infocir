<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use App\Models\Core\CoreFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonResource extends Model
{
    use HasDataTable;

    protected $table = 'learning_lesson_resources';

    protected $fillable = [
        'lesson_id',
        'type',
        'title',
        'file_id',
        'body',
        'order',
        'is_active',
    ];

    protected $casts = [
        'lesson_id' => 'integer',
        'file_id'   => 'integer',
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'title',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'file_id');
    }
}
