<?php

namespace App\Filament\Clusters\Cms\Resources\TagResource\Pages;

use App\Filament\Clusters\Cms\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
