<?php

namespace App\Models\Learning;

use App\Models\Auth\User;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Program extends Model
{
    use HasDataTable;

    protected $table = 'learning_programs';

    protected $fillable = [
        'name',
        'description',
        'certificate_template_id',
        'status',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'certificate_template_id' => 'integer',
        'is_active'               => 'boolean',
        'created_by'              => 'integer',
    ];

    public static $searchColumns = [
        'learning_programs.name',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Program $program) => $program->created_by = Auth::id());
    }

    public function certificateTemplate(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function programCourses(): HasMany
    {
        return $this->hasMany(ProgramCourse::class, 'program_id')->orderBy('order');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(ProgramDelivery::class, 'program_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
