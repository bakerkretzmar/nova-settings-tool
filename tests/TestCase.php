<?php

namespace Bakerkretzmar\SettingsTool\Tests;

use Bakerkretzmar\SettingsTool\SettingsToolServiceProvider;

use Illuminate\Support\Facades\Route;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        Route::middlewareGroup('nova', []);
    }

    protected function getPackageProviders($app)
    {
        return [
            SettingsToolServiceProvider::class,
        ];
    }
}
