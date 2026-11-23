<?php

namespace App\Models;

use Database\Factories\RepairDeviceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A device (or brand) V-ESA repairs, e.g. "Yamaha" pianos or "PlayStation 5", with the repairs it offers.
 */
#[Fillable(['family', 'name', 'description', 'image', 'is_published', 'sort_order'])]
class RepairDevice extends Model
{
    /** @use HasFactory<RepairDeviceFactory> */
    use HasFactory;

    /**
     * Sections of the repair page, keyed by stored value, in display order.
     *
     * @var array<string, string>
     */
    public const FAMILIES = [
        'instruments' => 'Instruments de musique',
        'consoles' => 'Consoles de jeux',
        'autres' => 'Autres appareils',
    ];

    /**
     * Short introduction shown for each section.
     *
     * @var array<string, string>
     */
    public const FAMILY_INTROS = [
        'instruments' => 'Pianos numériques, synthétiseurs et claviers : nous réparons Yamaha, Korg, Roland et toutes les autres marques.',
        'consoles' => 'Chaque console a ses pannes typiques. Voici les réparations que nous effectuons, modèle par modèle.',
        'autres' => 'Et tous vos autres appareils électroniques, pour les particuliers comme pour les professionnels.',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return HasMany<RepairService, $this>
     */
    public function services(): HasMany
    {
        return $this->hasMany(RepairService::class)->orderBy('sort_order');
    }

    /**
     * Devices shown on the public site, in the admin's chosen order.
     *
     * @param  Builder<RepairDevice>  $query
     */
    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('is_published', true)->orderBy('sort_order')->orderBy('name');
    }

    public function familyLabel(): string
    {
        return self::FAMILIES[$this->family] ?? $this->family;
    }
}
