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
            })
            ->keyBy('key')
            ->all();

        return response()->json($settings);
    }

    public function write(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $this->store->put($key, $value);
        }

        return response()->json();
    }
}
