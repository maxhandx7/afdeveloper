<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Business extends Model
{
    public const CACHE_KEY = 'business.current';

    protected $fillable = [
        'name', 'description', 'mision', 'vision', 'logo',
        'mail', 'address', 'phone', 'nit', 'configurations',
    ];

    protected function casts(): array
    {
        return ['configurations' => 'array'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Datos del sitio, cacheados para no consultar la BD en cada request.
     *
     * Se cachea un array y no el modelo: Laravel 13 no deserializa objetos
     * desde la caché por seguridad (config cache.serializable_classes).
     */
    public static function current(): self
    {
        $attributes = Cache::rememberForever(
            self::CACHE_KEY,
            fn () => static::query()->first()?->getAttributes(),
        );

        if ($attributes === null) {
            Cache::forget(self::CACHE_KEY); // que no quede "vacío" cacheado para siempre

            return new static([
                'name' => 'AF Developer',
                'mail' => config('afdeveloper.contact_email'),
                'configurations' => [],
            ]);
        }

        return (new static)->newFromBuilder($attributes);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        return data_get($this->configurations, $key, $default);
    }

    public function shortDescription(): string
    {
        return $this->setting('seo_description')
            ?: Str::limit(trim(strip_tags((string) $this->description)), 155);
    }
}
