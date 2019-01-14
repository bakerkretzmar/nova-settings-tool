<?php

namespace Bakerkretzmar\SettingsTool\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Bakerkretzmar\SettingsTool\SettingsToolServiceProvider;

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
