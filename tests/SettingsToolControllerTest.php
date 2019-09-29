<?php

namespace Bakerkretzmar\NovaSettingsTool\Tests;

use Illuminate\Support\Facades\Storage;

class SettingsToolControllerTest extends TestCase
{
    /** @test */
    public function can_read_settings()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'key' => 'test_setting',
            'value' => 'https://example.com',
        ]);
    }

    /** @test */
    public function can_read_settings_from_custom_path()
    {
        Storage::disk('public')->put(
            'custom/configurations.json',
            file_get_contents(__DIR__ . '/stubs/settings.json')
        );

        config(['nova-settings-tool.path' => base_path() . '/storage/app/public/custom/configurations.json']);

        $response = $this->get('nova-vendor/settings-tool');

        $response->assertSuccessful();
        $response->assertJsonFragment([
            'key' => 'test_setting',
            'value' => 'https://example.com',
        ]);
    }

    /** @test */
    public function can_fill_default_setting_metadata_automatically()
    {
        $response = $this->get('nova-vendor/settings-tool');

        $response->assertJsonFragment([
            'key' => 'setting_with_no_metadata',
            'type' => 'text',
            'label' => 'Setting_with_no_metadata',
            'value' => null,
        ]);
    }

    /** @test */
    public function can_write_settings()
    {
        $response = $this->postJson('nova-vendor/settings-tool', [
            'test_setting' => 'http://google.ca',
        ]);

        $response->assertSuccessful();
        $this->assertArrayHasKey('test_setting', json_decode(Storage::get('settings.json'), true));
        $this->assertSame('http://google.ca', json_decode(Storage::get('settings.json'), true)['test_setting']);
    }
}
