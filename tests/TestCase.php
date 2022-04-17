<?php

namespace Tests;

use Illuminate\Support\Facades\Storage;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();

        Storage::put(
            'settings.json',
            file_get_contents(__DIR__ . '/stubs/settings.json')
        );

        config(['nova-settings-tool' => include 'stubs/nova-settings-tool.php']);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Bakerkretzmar\NovaSettingsTool\SettingsToolServiceProvider::class,
        ];
    }
}
