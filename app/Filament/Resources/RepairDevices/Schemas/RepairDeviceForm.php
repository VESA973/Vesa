<?php

namespace App\Filament\Resources\RepairDevices\Schemas;

use App\Models\RepairDevice;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RepairDeviceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Appareil')
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom (marque ou modèle)')
                            ->placeholder('Yamaha, PlayStation 5…')
                            ->required()
                            ->maxLength(120),
                        Textarea::make('description')
                            ->label('Présentation courte')
                            ->rows(3),
                    ]),
                Section::make('Publication')
                    ->columnSpan(1)
                    ->schema([
                        Select::make('family')
                            ->label('Rubrique')
                            ->options(RepairDevice::FAMILIES)
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Affiché sur le site')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->label('Ordre')
                            ->numeric()
                            ->default(0),
                        FileUpload::make('image')
                            ->label('Photo (facultatif)')
                            ->image()
                            ->disk('public')
                            ->directory('repairs')
                            ->maxSize(4096),
                    ]),
                Section::make('Réparations effectuées')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('services')
                            ->label('')
                            ->relationship()
                            ->orderColumn('sort_order')
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->addActionLabel('Ajouter une réparation')
                            ->columns(3)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Réparation')
                                    ->required()
                                    ->maxLength(120),
                                TextInput::make('description')
                                    ->label('Détail (facultatif)')
                                    ->maxLength(255),
                                TextInput::make('price_from')
                                    ->label('À partir de (facultatif)')
                                    ->numeric()
                                    ->minValue(0)
                                    ->suffix('€'),
                            ]),
                    ]),
            ]);
    }
}
