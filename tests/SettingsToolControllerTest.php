<?php

namespace Bakerkretzmar\SettingsTool\Tests;

use Bakerkretzmar\SettingsTool\Http\Controllers\SettingsToolController;

use Illuminate\Support\Facades\Storage;

use Spatie\Valuestore\Valuestore;

class SettingsToolControllerTest extends TestCase
{
    /** @test */
    public function read_settings_file()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment(['test_string' => 'Hello']);
    }

    /** @test */
    public function read_settings_file_from_custom_path()
    {
        Storage::put(
            'custom/configurations.json',
            file_get_contents(__DIR__.'/stubs/settings.json')
        );

        config(['settings.path' => 'custom/configurations.json']);

        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment(['test_string' => 'Hello']);
    }

    /** @test */
    public function read_settings_file_from_custom_disk()
    {
        config(['filesystems.disks.custom' => [
            'driver' => 'local',
            'root' => storage_path('custom'),
        ]]);

        Storage::fake('custom');

        Storage::disk('custom')->put(
            'app/settings.json',
            file_get_contents(__DIR__.'/stubs/settings.json')
        );

        config(['settings.disk' => 'custom']);

        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment(['test_string' => 'Hello']);
    }

/** @test */
    public function read_settings_file_from_custom_path_and_disk()
    {
        config(['filesystems.disks.custom' => [
            'driver' => 'local',
            'root' => storage_path('custom'),
        ]]);

        Storage::fake('custom');

        Storage::disk('custom')->put(
            'folder/options.json',
            file_get_contents(__DIR__.'/stubs/settings.json')
        );

        config(['settings.disk' => 'custom']);
        config(['settings.path' => 'folder/options.json']);

        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment(['test_string' => 'Hello']);
    }

    /** @test */
    public function read_settings_values()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertSuccessful();
        $response->assertJsonFragment(['test_string' => 'Hello']);
    }

    /** @test */
    public function read_settings_config()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment(['name' => 'Test String']);
    }

    // set default settings values
    // don't return values not in configs
    // allow empty configs

    /** @test */
    // function it_sets_the_settings()
    // {
    //     $this
    //         ->postJson('nova-vendor/settings-tool', [
    //             'settings' => [
    //                 'test' => 'oh really?',
    //                 'fact' => true,
    //             ],
    //         ])
    //         ->assertSuccessful()
    //         ->assertJson([
    //             'test' => 'oh really?',
    //             'fact' => true,
    //         ]);
    // }
}
