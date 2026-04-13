<?php

namespace App\Common\Traits;

use App\Models\Core\Entity;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasEntity
{
    public static function bootHasEntity(): void
    {
        static::created(function ($model) {
            $model->entity()->create([]);
        });

        static::deleting(function ($model) {
            $model->entity()->delete();
        });
    }

    public function entity(): MorphOne
    {
        return $this->morphOne(Entity::class, 'entityable');
    }
}
