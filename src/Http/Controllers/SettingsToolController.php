<?php

namespace Bakerkretzmar\SettingsTool\Http\Controllers;

use Spatie\Valuestore\Valuestore;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsToolController extends Controller
{

    /** @var string */
    protected $settingsPath;

    public function __construct(string $settingsPath = null)
    {
        $this->settingsPath = $settingsPath ?? storage_path(config('settings.path'));
    }

    public function read(Request $request)
    {
        $settings = Valuestore::make($this->settingsPath)->all();

        $settingConfig = config('settings.groups');

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

    public function write(Request $request)
    {
        $settings = Valuestore::make($this->settingsPath);

        foreach ($request->settings as $setting => $value) {
            $settings->put($setting, $value);
        }

        return response($settings->all(), 202);
    }
}
