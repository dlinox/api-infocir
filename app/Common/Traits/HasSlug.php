<?php

namespace App\Common\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (!empty($model->slug) || empty($model->name)) {
                return;
            }

            $base = Str::slug($model->name) ?: 'item';
            $slug = $base;
            $suffix = 2;

            while (
                static::query()
                    ->where('slug', $slug)
                    ->when($model->getKey(), fn ($query) => $query->where($model->getKeyName(), '!=', $model->getKey()))
                    ->exists()
            ) {
                $slug = $base . '-' . $suffix++;
            }

            $model->slug = $slug;
        });
    }
}
