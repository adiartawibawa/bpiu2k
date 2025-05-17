<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', Post::count())
                ->description(Post::published()->count() . ' published')
                ->descriptionIcon('heroicon-o-document-text')
                ->chart($this->getPostChartData())
                ->color('primary'),

            Stat::make('Total Pages', Page::count())
                ->description(Page::published()->count() . ' published')
                ->descriptionIcon('heroicon-o-document')
                ->chart($this->getPageChartData())
                ->color('success'),

            Stat::make('Total Categories', Category::count())
                ->description(Category::active()->count() . ' active')
                ->descriptionIcon('heroicon-o-tag')
                ->chart($this->getCategoryChartData())
                ->color('warning'),

            Stat::make('Total Users', User::count())
                ->description(User::where('status', User::STATUS_ACTIVE)->count() . ' active')
                ->descriptionIcon('heroicon-o-users')
                ->chart($this->getUserChartData())
                ->color('danger'),

            Stat::make('Media Library', Media::count())
                ->description($this->getMediaSize())
                ->descriptionIcon('heroicon-o-photo')
                ->color('info'),
        ];
    }

    protected function getPostChartData(): array
    {
        return Post::select(
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get()
            ->pluck('count')
            ->toArray();
    }

    protected function getPageChartData(): array
    {
        return Page::select(
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get()
            ->pluck('count')
            ->toArray();
    }

    protected function getCategoryChartData(): array
    {
        return Category::select(
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get()
            ->pluck('count')
            ->toArray();
    }

    protected function getUserChartData(): array
    {
        return User::select(
            DB::raw('count(*) as count'),
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month")
        )
            ->groupBy('month')
            ->orderBy('month')
            ->limit(6)
            ->get()
            ->pluck('count')
            ->toArray();
    }

    protected function getMediaSize(): string
    {
        $size = Media::sum('size') / 1024 / 1024; // Convert to MB
        return number_format($size, 2) . ' MB';
    }
}
