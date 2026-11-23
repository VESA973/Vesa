<?php

namespace App\Filament\Resources\ContactRequests\Tables;

use App\Models\ContactRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('Reçue le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('service')
                    ->label('Activité')
                    ->formatStateUsing(fn (string $state): string => ContactRequest::SERVICES[$state] ?? $state)
                    ->badge(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('company')
                    ->label('Entreprise')
                    ->searchable()
                    ->toggleable(),
                ToggleColumn::make('is_handled')
                    ->label('Traitée'),
            ])
            ->filters([
                SelectFilter::make('service')
                    ->label('Activité')
                    ->options(ContactRequest::SERVICES),
                TernaryFilter::make('is_handled')
                    ->label('Traitée'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
