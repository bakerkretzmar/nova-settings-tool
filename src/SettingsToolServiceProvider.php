<?php

namespace Bakerkretzmar\NovaSettingsTool;

use Bakerkretzmar\NovaSettingsTool\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SettingsToolServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nova-settings-tool.php' => config_path('nova-settings-tool.php'),
        ], 'nova-settings-tool');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'settings-tool');

        $this->app->booted(function () {
            $this->routes();
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('nova-vendor/settings-tool')
                ->group(__DIR__.'/../routes/api.php');
    }
}
