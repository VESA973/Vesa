<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'slug', 'category', 'client', 'client_logo', 'year', 'excerpt', 'challenge', 'solution', 'result', 'technologies', 'url', 'cover_image', 'gallery', 'is_featured', 'is_published', 'sort_order'])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * Website types shown in the portfolio, keyed by stored value.
     *
     * @var array<string, string>
     */
    public const CATEGORIES = [
        'vitrine' => 'Site vitrine',
        'ecommerce' => 'E-commerce',
        'application' => 'Application web',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'technologies' => 'array',
            'gallery' => 'array',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    /**
     * Use the slug in public URLs.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Only projects visible on the public site, in the admin's chosen order.
     *
     * @param  Builder<Project>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort_order')->latest('year');
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }
}
