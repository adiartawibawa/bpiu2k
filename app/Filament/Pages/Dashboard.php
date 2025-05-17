<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RecentPagesTable;
use App\Filament\Widgets\RecentPostsTable;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SystemStatusWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            AccountWidget::class,
            StatsOverview::class,
            SystemStatusWidget::class,
            RecentPostsTable::class,
            RecentPagesTable::class,
        ];
    }

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }
}
