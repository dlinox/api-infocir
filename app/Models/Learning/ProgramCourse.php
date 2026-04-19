<?php

namespace App\Models\Learning;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramCourse extends Model
{
    protected $table = 'learning_program_courses';

    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'course_id',
        'order',
        'is_required',
    ];

    protected $casts = [
        'program_id'  => 'integer',
        'course_id'   => 'integer',
        'order'       => 'integer',
        'is_required' => 'boolean',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
