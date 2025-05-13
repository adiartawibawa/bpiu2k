<?php

namespace App\Filament\Clusters\Cms\Resources\PostResource\Pages;

use App\Filament\Clusters\Cms\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}
