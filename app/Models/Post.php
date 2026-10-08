<?php

namespace App\Models;

use App\Enums\PublishStatus;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasSlug;

    protected $fillable = [
        'image', 'title', 'slug', 'excerpt', 'meta_description',
        'long_description', 'status', 'published_at',
    ];

    protected $attributes = ['status' => 'DESACTIVATED'];

    protected function casts(): array
    {
        return [
            'status' => PublishStatus::class,
            'published_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', PublishStatus::Published)
            ->where(fn (Builder $q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->latest('published_at');
    }

    /** Resumen para tarjetas y meta tags cuando no se escribió uno a mano. */
    public function summary(int $limit = 160): string
    {
        return $this->excerpt
            ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $this->long_description))), $limit);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->long_description)) / 200));
    }
}
