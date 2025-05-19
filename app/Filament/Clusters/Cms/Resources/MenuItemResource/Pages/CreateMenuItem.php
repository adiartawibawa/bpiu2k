<?php

namespace App\Filament\Clusters\Cms\Resources\MenuItemResource\Pages;

use App\Filament\Clusters\Cms\Resources\MenuItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuItem extends CreateRecord
{
    protected static string $resource = MenuItemResource::class;
}
