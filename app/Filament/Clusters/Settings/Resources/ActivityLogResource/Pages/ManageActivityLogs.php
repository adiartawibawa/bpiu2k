<?php

namespace App\Filament\Clusters\Settings\Resources\ActivityLogResource\Pages;

use App\Filament\Clusters\Settings\Resources\ActivityLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageActivityLogs extends ManageRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
