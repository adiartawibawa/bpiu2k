<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Settings\EmailSettings;
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

        // Load email settings dari database
        try {
            /** @var EmailSettings $settings */
            $settings = app(EmailSettings::class);

            // Inject ke konfigurasi runtime Laravel
            config(['mail' => $settings->toMailConfig()]);
        } catch (\Throwable $e) {
            // Bisa log kalau mau debugging di awal boot
            logger()->warning('Failed to load email settings', [
                'exception' => $e,
            ]);
        }
    }
}
