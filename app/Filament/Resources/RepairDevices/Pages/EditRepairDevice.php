<?php

namespace App\Filament\Resources\RepairDevices\Pages;

use App\Filament\Resources\RepairDevices\RepairDeviceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRepairDevice extends EditRecord
{
    protected static string $resource = RepairDeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
