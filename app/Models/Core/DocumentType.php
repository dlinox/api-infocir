<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasDataTable;

    protected $table = 'core_document_types';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    public static $searchColumns = [
        'code',
        'name',
    ];

    public function persons()
    {
        return $this->hasMany(Person::class, 'document_type', 'code');
    }
}
