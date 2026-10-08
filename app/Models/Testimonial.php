<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['image', 'name', 'role', 'description', 'is_published', 'sort_order'];

    protected $attributes = ['is_published' => true, 'sort_order' => 0];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort_order')->latest('id');
    }
}
