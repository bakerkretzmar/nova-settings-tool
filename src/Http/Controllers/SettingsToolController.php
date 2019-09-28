<?php

namespace Bakerkretzmar\SettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

use Spatie\Valuestore\Valuestore;

class SettingsToolController extends Controller
{
    protected $store;

    public function __construct()
    {
        $this->store = Valuestore::make(
            storage_path(config('nova-settings-tool.path', 'app/settings.json'))
        );
    }

    /**
     * Retrieve and format settings from a file.
     */
    public function read(Request $request)
    {
        $settings = Valuestore::make($this->path)->all();

        $settingConfig = config('settings.panels');

        foreach ($settingConfig as $object) {
            foreach ($object['settings'] as $settingObject) {
                if (! array_key_exists($settingObject['key'], $settings)) {
                    if ($settingObject['type'] == 'toggle') {
                        $settings[$settingObject['key']] = $settingObject['default'] ?? false;
                    } else {
                        $settings[$settingObject['key']] = '';
                    }
                }
            }
        }

        return response()->json([
            'settings' => $settings,
            'settingConfig' => $settingConfig,
        ]);
    }

    /**
     * Save updated settings to a file.
     */
    public function write(Request $request)
    {
        $settings = Valuestore::make($this->path);

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
        $settingConfig = config('settings.panels');

        foreach ($settingConfig as $object) {
            foreach ($object['settings'] as $settingObject) {
                if ($settingObject['key'] === $key) {
                    return $settingObject;
                }
            }
        }

        return null;
    }
}
