<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\CoreFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierGallery extends Model
{
    use HasDataTable;

    protected $table = 'dairy_supplier_galeries';

    protected $fillable = [
        'supplier_id',
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'file_id');
    }
}
