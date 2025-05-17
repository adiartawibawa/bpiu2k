<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Settings';

    protected static ?int $navigationSort = 100;

    protected static ?string $navigationGroup = 'Manajemen Aplikasi';

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }
}
