<?php

namespace App\Models\Learning;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Area extends Model
{
    use HasDataTable;

    protected $table = 'learning_areas';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'created_by' => 'integer',
        'is_active'  => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];

    protected static function booted(): void
    {
        static::creating(fn (Area $area) => $area->created_by = Auth::id());
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
