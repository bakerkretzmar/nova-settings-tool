<?php

namespace Bakerkretzmar\SettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Spatie\Valuestore\Valuestore;

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
                    $settings[$settingObject['key']] = $settingObject['type'] == 'toggle' ? false : '';
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

        foreach ($request->settings as $setting => $value) {
            $settings->put($setting, $value);
        }

        return response($settings->all(), 202);
    }
}
