<?php

namespace Bakerkretzmar\NovaSettingsTool\Tests;

use Illuminate\Support\Facades\Storage;

class SettingsToolControllerTest extends TestCase
{
    /** @test */
    public function can_retrieve_settings()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'key' => 'test_setting',
            'value' => 'https://example.com',
        ]);
    }

    /** @test */
    public function can_retrieve_settings_from_custom_path()
    {
        Storage::put(
            'custom/configurations.json',
            file_get_contents(__DIR__ . '/stubs/settings.json')
        );

        config(['nova-settings-tool.path' => 'app/custom/configurations.json']);

        $response = $this->get('nova-vendor/settings-tool');

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'key' => 'test_setting',
            'value' => 'https://example.com',
        ]);
    }

    /** @test */
    public function can_set_default_setting_metadata_automatically()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment([
            'key' => 'setting_with_no_metadata',
            'type' => 'text',
            'label' => 'setting_with_no_metadata',
            'value' => null,
        ]);
    }

    // DON'T set default settings values
    // handle missing values
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
