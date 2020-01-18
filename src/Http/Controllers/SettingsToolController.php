<?php

namespace Bakerkretzmar\NovaSettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Client as ClientResource;
use App\Models\Client;

class SettingsToolController
{
    protected $store;

    public function __construct()
    {
        $this->store = Valuestore::make(
            config('nova-settings-tool.path', storage_path('app/settings.json'))
        );
    }

    public function fields(NovaRequest $request)
    {
        $values = $this->store->all();
        $panels = collect(config('nova-settings-tool.panels'));

        return response()->json(
            [
                'data' => [
                    'panels' => $panels,
                    'values' => $values,
                    'settings' => [
                        'visualized' => config('nova-settings-tool.panel-visualized', 'visualized'),
                        'remember_active' => config('nova-settings-tool.accordion-active-remember', true)
                    ]
                ],
            ]
        );
    }

    public function write(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            $this->store->put($key, $value);
        }

        return response()->json();
    }
}
