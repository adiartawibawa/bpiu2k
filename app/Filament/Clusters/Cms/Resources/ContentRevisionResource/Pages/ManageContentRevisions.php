<?php

namespace App\Filament\Clusters\Cms\Resources\ContentRevisionResource\Pages;

use App\Filament\Clusters\Cms\Resources\ContentRevisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageContentRevisions extends ManageRecords
{
    protected static string $resource = ContentRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
