<?php

namespace App\Filament\Clusters\Cms\Resources\MenuResource\Pages;

use App\Filament\Clusters\Cms\Resources\MenuItemResource\Widgets\MenuItemWidget;
use App\Filament\Clusters\Cms\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // MenuItemWidget::class,
        ];
    }
}
