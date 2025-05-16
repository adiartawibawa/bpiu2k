<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class UserManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'User Management';

    protected static ?int $navigationSort = 100;

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    public static function getNavigationLabel(): string
    {
        return __('Manajemen Pengguna');
    }
}
