<?php

namespace App\Models\Dairy;

use App\Common\Traits\HasDataTable;
use App\Models\Core\CoreFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductGallery extends Model
{
    use HasDataTable;

    protected $table = 'dairy_product_galeries';

    protected $fillable = [
        'product_id',
        'presentation_id',
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(CoreFile::class, 'file_id');
    }
}
