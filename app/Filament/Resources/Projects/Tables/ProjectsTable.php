<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Models\Project;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('')
                    ->disk('public'),
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->description(fn (Project $record): ?string => $record->client),
                TextColumn::make('category')
                    ->label('Type de site')
                    ->formatStateUsing(fn (string $state): string => Project::CATEGORIES[$state] ?? $state)
                    ->badge(),
                TextColumn::make('year')
                    ->label('Année')
                    ->sortable(),
                ToggleColumn::make('is_published')
                    ->label('Publié'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Type de site')
                    ->options(Project::CATEGORIES),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
