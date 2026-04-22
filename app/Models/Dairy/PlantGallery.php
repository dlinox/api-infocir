<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\CoreFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantGallery extends Model
{
    use HasDataTable;

    protected $table = 'dairy_plant_galeries';

    protected $fillable = [
        'plant_id',
        'file_id',
        'caption',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'caption',
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'file_id');
    }
}
