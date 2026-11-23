<?php

namespace App\Filament\Resources\ContactRequests\Schemas;

use App\Models\ContactRequest;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('service')
                    ->label('Activité')
                    ->formatStateUsing(fn (string $state): string => ContactRequest::SERVICES[$state] ?? $state)
                    ->badge(),
                TextEntry::make('name')
                    ->label('Nom'),
                TextEntry::make('email')
                    ->label('E-mail')
                    ->copyable(),
                TextEntry::make('phone')
                    ->label('Téléphone')
                    ->placeholder('-'),
                TextEntry::make('company')
                    ->label('Entreprise')
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->label('Message')
                    ->columnSpanFull(),
                IconEntry::make('is_handled')
                    ->label('Traitée')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Reçue le')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
