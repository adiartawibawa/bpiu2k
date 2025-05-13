<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(\App\Observers\PostObserver::class);
        Category::observe(\App\Observers\CategoryObserver::class);
        Page::observe(\App\Observers\PageObserver::class);
    }
}
