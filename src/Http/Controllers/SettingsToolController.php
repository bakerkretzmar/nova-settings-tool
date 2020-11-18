<?php

namespace Bakerkretzmar\NovaSettingsTool\Http\Controllers;

use Bakerkretzmar\NovaSettingsTool\Events\SettingsChanged;
use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;

class SettingsToolController
{
    protected $store;

    public function __construct()
    {
        $this->store = Valuestore::make(
            config('nova-settings-tool.path', storage_path('app/settings.json'))
        );
    }

    public function read()
    {
        $values = $this->store->all();

        $settings = collect(config('nova-settings-tool.settings'));

        $panels = $settings->where('panel', '!=', null)->pluck('panel')->unique()
            ->flatMap(function ($panel) use ($settings) {
                return [$panel => $settings->where('panel', $panel)->pluck('key')->all()];
            })
            ->when($settings->where('panel', null)->isNotEmpty(), function ($collection) use ($settings) {
                return $collection->merge(['_default' => $settings->where('panel', null)->pluck('key')->all()]);
            })
            ->all();

        $settings = $settings->map(function ($setting) use ($values) {
            return array_merge([
                'type' => 'text',
                'label' => ucfirst($setting['key']),
                'value' => $values[$setting['key']] ?? null,
            ], $setting);
        })
            ->keyBy('key')
            ->all();

        return response()->json(compact('settings', 'panels'));
    }

    public function write(Request $request)
    {
        $oldSettings = $this->store->all();

        foreach ($request->all() as $key => $value) {
            $this->store->put($key, $value);
        }

        event(new SettingsChanged($this->store->all(), $oldSettings));

        return response()->json();
    }
}
