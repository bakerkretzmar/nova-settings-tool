<?php

namespace Tests;

use Bakerkretzmar\NovaSettingsTool\Events\SettingsChanged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;

class SettingsToolControllerTest extends TestCase
{
    /** @test */
    public function read_settings()
    {
        $this->get('nova-vendor/nova-settings-tool')
            ->assertSuccessful()
            ->assertJsonFragment([
                'key' => 'test_setting',
                'value' => 'https://example.com',
            ]);
    }

    /** @test */
    public function read_settings_from_custom_path()
    {
        Storage::disk('public')->put(
            'custom/configurations.json',
            file_get_contents(__DIR__ . '/stubs/settings.json')
        );

        config(['nova-settings-tool.path' => base_path() . '/storage/app/public/custom/configurations.json']);

        $this->get('nova-vendor/nova-settings-tool')
            ->assertSuccessful()
            ->assertJsonFragment([
                'key' => 'test_setting',
                'value' => 'https://example.com',
            ]);
    }

    /** @test */
    public function fill_default_setting_metadata_automatically()
    {
        $this->get('nova-vendor/nova-settings-tool')
            ->assertJsonFragment([
                'key' => 'setting_with_no_metadata',
                'type' => 'text',
                'label' => 'Setting_with_no_metadata',
                'value' => null,
            ]);
    }

    /** @test */
    public function write_settings()
    {
        $this->postJson('nova-vendor/nova-settings-tool', [
            'test_setting' => 'http://google.ca',
        ])->assertSuccessful();
        $this->assertArrayHasKey('test_setting', json_decode(Storage::get('settings.json'), true));
        $this->assertSame('http://google.ca', json_decode(Storage::get('settings.json'), true)['test_setting']);
    }

    /** @test */
    public function emit_event_when_settings_updated()
    {
        Event::fake();

        $this->postJson('nova-vendor/nova-settings-tool', [
            'test_setting' => 'http://google.ca',
        ])->assertSuccessful();

        Event::assertDispatched(function (SettingsChanged $event) {
            $this->assertSame('http://google.ca', $event->settings['test_setting']);
            $this->assertSame('https://example.com', $event->oldSettings['test_setting']);
            return true;
        });
    }
}
