<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplySettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Apply site name
        config(['app.name' => Setting::get('site_name', config('app.name'))]);

        // Apply colors
        config([
            'settings.primary_color' => Setting::get('primary_color', '#3b82f6'),
            'settings.secondary_color' => Setting::get('secondary_color', '#64748b'),
        ]);

        return $next($request);
    }
}
