<?php

namespace Bakerkretzmar\NovaSettingsTool;

use Bakerkretzmar\NovaSettingsTool\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class SettingsToolServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/nova-settings-tool.php' => config_path('nova-settings-tool.php'),
        ], 'nova-settings-tool');

        $this->app->booted(function () {
            $this->routes();
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authorize::class], 'settings')
            ->group(__DIR__ . '/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-settings-tool')
            ->group(__DIR__ . '/../routes/api.php');
    }
}
