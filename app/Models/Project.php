<?php

namespace App\Models;

use App\Enums\PublishStatus;
use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasSlug;

    protected $fillable = [
        'image', 'title', 'slug', 'link', 'repo_url', 'description',
        'long_description', 'tech_stack', 'status', 'is_featured', 'sort_order',
    ];

    protected $attributes = ['status' => 'ACTIVE', 'is_featured' => false, 'sort_order' => 0];

    protected function casts(): array
    {
        return [
            'status' => PublishStatus::class,
            'tech_stack' => 'array',
            'is_featured' => 'boolean',
        ];
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', PublishStatus::Published)->orderBy('sort_order')->latest('id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
