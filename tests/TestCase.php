<?php

namespace Bakerkretzmar\SettingsTool\Tests;

use Bakerkretzmar\SettingsTool\SettingsToolServiceProvider;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Route::middlewareGroup('nova', []);

        Storage::fake();

        Storage::put(
            'app/settings.json',
            file_get_contents(__DIR__.'/stubs/settings.json')
        );

        config(['nova-settings-tool' => include 'stubs/nova-settings-tool.php']);
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingsToolServiceProvider::class,
        ];
    }
}
