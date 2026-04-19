<?php

namespace App\Models\Learning;

use App\Models\Core\CoreFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CertificateTemplateSignature extends Model
{
    protected $table = 'learning_certificate_template_signatures';

    protected $fillable = [
        'template_id',
        'signature_file_id',
        'title',
        'subtitle',
        'x',
        'y',
        'width',
        'order',
    ];

    protected $casts = [
        'template_id'       => 'integer',
        'signature_file_id' => 'integer',
        'x'                 => 'float',
        'y'                 => 'float',
        'width'             => 'float',
        'order'             => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'template_id');
    }

    public function signatureFile(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'signature_file_id');
    }
}
