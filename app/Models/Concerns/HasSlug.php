<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** Genera un slug único a partir del título si no se escribe uno a mano. */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            if (blank($model->slug)) {
                $model->slug = $model->uniqueSlug(Str::slug($model->title) ?: Str::random(8));
            }
        });
    }

    public function uniqueSlug(string $base): string
    {
        $slug = $base;
        $i = 2;

        while (static::query()->where('slug', $slug)->whereKeyNot($this->getKey())->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
