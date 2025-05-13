<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('settings', function () {
            return Setting::allCached();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $view->with('settings', app('settings'));
        });

        // Blade directive for settings
        Blade::directive('setting', function ($key) {
            return "<?php echo app('settings')->get($key); ?>";
        });
    }
}
