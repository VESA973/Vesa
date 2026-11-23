<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Project;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Projet')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->required()
                            ->maxLength(120)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                        TextInput::make('slug')
                            ->label('Adresse (URL)')
                            ->prefix('/realisations/')
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true),
                        TextInput::make('client')
                            ->label('Client'),
                        TextInput::make('year')
                            ->label('Année')
                            ->numeric()
                            ->minValue(2000)
                            ->maxValue(2100),
                        Textarea::make('excerpt')
                            ->label('Résumé (une phrase)')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('challenge')
                            ->label('Le besoin')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('solution')
                            ->label('La solution')
                            ->rows(4)
                            ->columnSpanFull(),
                        Textarea::make('result')
                            ->label('Le résultat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Publication')
                    ->columnSpan(1)
                    ->schema([
                        Toggle::make('is_published')
                            ->label('Publié sur le site'),
                        Select::make('category')
                            ->label('Type de site')
                            ->options(Project::CATEGORIES)
                            ->required(),
                        TagsInput::make('technologies')
                            ->label('Technologies')
                            ->placeholder('Laravel, Filament…'),
                        TextInput::make('url')
                            ->label('Lien du site')
                            ->url(),
                        TextInput::make('sort_order')
                            ->label('Ordre')
                            ->numeric()
                            ->default(0),
                    ]),
                Section::make('Images')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('cover_image')
                            ->label('Image principale')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('projects')
                            ->maxSize(4096),
                        FileUpload::make('client_logo')
                            ->label('Logo du client (bandeau « Ils nous font confiance »)')
                            ->image()
                            ->disk('public')
                            ->directory('clients')
                            ->maxSize(1024),
                        FileUpload::make('gallery')
                            ->label('Galerie (captures, photos avant/après)')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('public')
                            ->directory('projects')
                            ->maxSize(4096),
                    ]),
            ]);
    }
}
