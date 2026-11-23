<?php

namespace App\Filament\Resources\RepairDevices\Tables;

use App\Models\RepairDevice;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class RepairDevicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->defaultGroup(
                Group::make('family')
                    ->label('Rubrique')
                    ->getTitleFromRecordUsing(fn (RepairDevice $record): string => $record->familyLabel()),
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Appareil')
                    ->searchable(),
                TextColumn::make('family')
                    ->label('Rubrique')
                    ->formatStateUsing(fn (string $state): string => RepairDevice::FAMILIES[$state] ?? $state)
                    ->badge(),
                TextColumn::make('services_count')
                    ->label('Réparations')
                    ->counts('services'),
                ToggleColumn::make('is_published')
                    ->label('Affiché'),
            ])
            ->filters([
                SelectFilter::make('family')
                    ->label('Rubrique')
                    ->options(RepairDevice::FAMILIES),
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
