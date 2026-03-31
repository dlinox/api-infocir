<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Infrastructure extends Model
{
    use HasDataTable;

    protected $table = 'core_infrastructures';

    protected $fillable = [
        'infrastructurable_type',
        'infrastructurable_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $searchColumns = [
        'infrastructurable_type',
    ];

    public function infrastructurable(): MorphTo
    {
        return $this->morphTo();
    }
}
