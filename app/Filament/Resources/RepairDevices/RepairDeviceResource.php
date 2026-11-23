<?php

namespace App\Filament\Resources\RepairDevices;

use App\Filament\Resources\RepairDevices\Pages\CreateRepairDevice;
use App\Filament\Resources\RepairDevices\Pages\EditRepairDevice;
use App\Filament\Resources\RepairDevices\Pages\ListRepairDevices;
use App\Filament\Resources\RepairDevices\Schemas\RepairDeviceForm;
use App\Filament\Resources\RepairDevices\Tables\RepairDevicesTable;
use App\Models\RepairDevice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RepairDeviceResource extends Resource
{
    protected static ?string $model = RepairDevice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?string $modelLabel = 'appareil réparé';

    protected static ?string $pluralModelLabel = 'réparations';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return RepairDeviceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RepairDevicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRepairDevices::route('/'),
            'create' => CreateRepairDevice::route('/create'),
            'edit' => EditRepairDevice::route('/{record}/edit'),
        ];
    }
}
