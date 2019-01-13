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

        $appSettings = config('settings.groups');

        foreach ($appSettings as $group) {
            foreach ($group as $item) {
                $item['value'] = $settings[$item['key']];
            }
        }

        return $appSettings;
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
