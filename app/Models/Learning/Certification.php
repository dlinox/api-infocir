<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certification extends Model
{
    use HasDataTable;

    protected $table = 'learning_certifications';

    public array $searchColumns = [
        'learning_certifications.certificate_number',
        'core_persons.name',
        'core_persons.paternal_surname',
    ];

    protected $fillable = [
        'enrollment_id',
        'template_id',
        'certificate_number',
        'issued_at',
        'expires_at',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
        'template_id'   => 'integer',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }
}
