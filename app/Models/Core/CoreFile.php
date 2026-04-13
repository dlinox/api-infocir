<?php

namespace App\Models\Core;

use App\Common\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoreFile extends Model
{
    use SoftDeletes;

    protected $table = 'core_files';

    protected $fillable = [
        'storage_disk',
        'filename',
        'filepath',
        'mime_type',
        'caption',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function getUrlAttribute(): ?string
    {
        return FileHelper::getFileUrl($this->storage_disk, $this->filepath);
    }
}
