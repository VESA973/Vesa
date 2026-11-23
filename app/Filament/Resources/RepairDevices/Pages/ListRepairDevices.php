<?php

namespace App\Filament\Resources\RepairDevices\Pages;

use App\Filament\Resources\RepairDevices\RepairDeviceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRepairDevices extends ListRecords
{
    protected static string $resource = RepairDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
