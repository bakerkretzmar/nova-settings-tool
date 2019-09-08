<?php

namespace Bakerkretzmar\SettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\Valuestore\Valuestore;
use Illuminate\Routing\Controller;

class SettingsToolController extends Controller
{
    /**
     * Path to the settings file on disk.
     * @var string
     */
    protected $settingsPath;

    /**
     * Create a new controller instance.
     */
    public function __construct(string $settingsPath = null)
    {
        $this->settingsPath = $settingsPath ?? storage_path(config('settings.path', 'app/settings.json'));
    }

    /**
     * Retrieve and format settings from a file.
     */
    public function read(Request $request)
    {
        $settings = Valuestore::make($this->settingsPath)->all();

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
        $settings = Valuestore::make($this->settingsPath);

        foreach ($request->all() as $setting => $value) {
            $settingObject = $this->getSettingObject($setting);

            if ($settingObject['type'] === 'file' && $value instanceof UploadedFile) {
                $settings->put($setting, $value->storeAs($settingObject['path'], $value->getClientOriginalName(), $settingObject['disk']));
            } else if ($settingObject['type'] === 'toggle') {
                $settings->put($setting, $value === 'true');
            } else {
                $settings->put($setting, $value);
            }
        }

        return response($settings->all(), 202);
    }

    /**
     * Retrieve the config for a specified key
     */
    public function getSettingObject(string $key)
    {
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
