<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseModule extends Model
{
    use HasDataTable;

    protected $table = 'learning_course_modules';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'is_active',
    ];

    protected $casts = [
        'course_id' => 'integer',
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'title',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'module_id')->orderBy('order');
    }
}
