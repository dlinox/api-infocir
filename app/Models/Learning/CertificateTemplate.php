<?php

namespace App\Models\Learning;

use App\Models\Core\CoreFile;
use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificateTemplate extends Model
{
    use HasDataTable;

    protected $table = 'learning_certificate_templates';

    protected $fillable = [
        'name',
        'page_size',
        'orientation',
        'background_file_id',
        'fields',
        'validity_days',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'background_file_id' => 'integer',
        'fields'             => 'array',
        'validity_days'      => 'integer',
        'is_active'          => 'boolean',
        'created_by'         => 'integer',
    ];

    public static $searchColumns = [
        'learning_certificate_templates.name',
    ];

    public function backgroundFile(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'background_file_id');
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(CertificateTemplateSignature::class, 'template_id')->orderBy('order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
