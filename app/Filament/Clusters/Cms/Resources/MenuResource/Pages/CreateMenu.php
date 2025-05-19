<?php

namespace App\Filament\Clusters\Cms\Resources\MenuResource\Pages;

use App\Filament\Clusters\Cms\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}
