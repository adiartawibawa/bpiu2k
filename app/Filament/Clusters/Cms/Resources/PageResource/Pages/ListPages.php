<?php

namespace App\Filament\Clusters\Cms\Resources\PageResource\Pages;

use App\Filament\Clusters\Cms\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
