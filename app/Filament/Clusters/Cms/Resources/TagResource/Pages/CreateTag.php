<?php

namespace App\Filament\Clusters\Cms\Resources\TagResource\Pages;

use App\Filament\Clusters\Cms\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}
