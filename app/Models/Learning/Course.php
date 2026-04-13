<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasDataTable;

    protected $table = 'learning_courses';

    protected $fillable = [
        'name',
        'description',
        'area_id',
        'duration_min',
        'status',
        'created_by',
    ];

    protected $casts = [
        'area_id'        => 'integer',
        'duration_min'  => 'float',
        'created_by'     => 'integer',
    ];

    public static $searchColumns = [
        'learning_courses.name',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Course $course) => $course->created_by = Auth::id());
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
