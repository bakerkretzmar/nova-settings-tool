<?php

namespace Bakerkretzmar\SettingsTool\Http\Controllers;

use Spatie\Valuestore;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsToolController extends Controller
{

    private $settings;

    public function __construct()
    {
        $this->settings = Valuestore::make(storage_path(config('settings.path')));
    }

    public function write(Request $request)
    {
        foreach ($request->settings as $setting => $value) {
            $this->settings->put($setting, $value);
        }

        return response('success', 202);
    }
}
