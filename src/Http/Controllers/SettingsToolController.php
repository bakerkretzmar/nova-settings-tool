<?php

namespace Bakerkretzmar\NovaSettingsTool\Http\Controllers;

use Illuminate\Http\Request;

use Spatie\Valuestore\Valuestore;

class SettingsToolController
{
    protected $store;

    public function __construct()
    {
        $this->store = Valuestore::make(
            storage_path(config('nova-settings-tool.path', 'app/settings.json'))
        );
    }

    public function read()
    {
        $values = $this->store->all();

        $settings = collect(config('nova-settings-tool.settings'))
            ->map(function ($setting) use ($values) {
                return array_merge([
                    'type' => 'text',
                    'label' => $setting['key'],
                    'value' => $values[$setting['key']] ?? null,
                ], $setting);
            })->all();

        return response()->json($settings);
    }

    /**
     * Save updated settings to a file.
     */
    public function write(Request $request)
    {
        $settings = $this->store;

        foreach ($request->all() as $setting => $value) {
            if ($value instanceof UploadedFile) {
                $settingObject = $this->getSettingObject($setting);

                $settings->put($setting, $value->storeAs($settingObject['path'], $value->getClientOriginalName(), $settingObject['disk']));
            } else {
                $settings->put($setting, $value);
            }
        }

        return response($settings->all(), 202);
    }

    /**
     * Retrieve the config for a specified key
     */
    public function getSettingObject(string $key) {
        $config = config('nova-settings-tool.panels');

        foreach ($config as $object) {
            foreach ($object['settings'] as $settingObject) {
                if ($settingObject['key'] === $key) {
                    return $settingObject;
                }
            }
        }

        return null;
    }
}
