<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Cms extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Contents';

    protected static ?int $navigationSort = 99;

    public static function getNavigationLabel(): string
    {
        return __('Contents');
    }
}
