<?php

namespace App\Models;

use Database\Factories\ContactRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['service', 'name', 'email', 'phone', 'company', 'message', 'is_handled'])]
class ContactRequest extends Model
{
    /** @use HasFactory<ContactRequestFactory> */
    use HasFactory;

    /**
     * Services a visitor can pick on the contact form, keyed by stored value.
     *
     * @var array<string, string>
     */
    public const SERVICES = [
        'solutions' => 'Solutions pour entreprises',
        'reparation' => 'Réparation électronique',
        'autre' => 'Autre demande',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_handled' => 'boolean',
        ];
    }
}
