<?php

namespace App\Models\Learning;

use App\Models\Core\CoreFile;
use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'cover_image',
        'certificate_template_id',
        'status',
        'created_by',
    ];

    protected $casts = [
        'area_id'                  => 'integer',
        'cover_image'              => 'integer',
        'certificate_template_id'  => 'integer',
        'duration_min'             => 'float',
        'created_by'               => 'integer',
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

    public function coverImageFile(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'cover_image');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(CourseModule::class, 'course_id')->orderBy('order');
    }

    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function enrollments(): MorphMany
    {
        return $this->morphMany(Enrollment::class, 'enrollable');
    }
}
