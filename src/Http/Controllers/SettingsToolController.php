<?php

namespace Bakerkretzmar\NovaSettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
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
                    'panels' => $panels->map(function($panel) use ($values) {
                        $panel['fields'] = array_map(function ($field) use ($values) {
                            // Set the value into the field.
                            $field->value = $values[$field->attribute] ?? null;

                            if($field->component == 'code-field'){
                                if(!$field->value) $field->value = '';
                            }

                            // Remove delete option for files.
                            if ($field->component == 'file-field') {
                                $field->deletable(false);
                            }

                            return $field;
                            }, array_filter($panel['fields'], function($field){
                                return true;
                            }));

                        return $panel;
                    }),
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
        $values = $this->store->all();
        $panels = collect(config('nova-settings-tool.panels'));

        $fields = $panels->pluck('fields')->flatten();
        $attributes = $fields->pluck('attribute');

        $request_fields = $request->only($attributes->toArray());
        $prune_updated_files = [];
        foreach ($request_fields as $key => $value) {
            $field = $fields->firstWhere('attribute', $key);

            if ($field->component == 'file-field') {
                if (!$request->hasFile($key)) {
                    continue;
                }

                $last_value = $this->store->get($key);
                $dir = $field->getStorageDir();
                $disk = $field->getStorageDisk();
                $storage_dir = $dir ? $dir . '/' : '';

                $value = $request->file($key)->store($dir, $disk);

                if ($last_value && Storage::disk($disk)->exists($storage_dir . $last_value)) {
                    $prune_updated_files[] = [
                        'disk' => $disk,
                        'file' => $storage_dir . $last_value,
                    ];
                }

                $request_fields[$key] = $value;
            }

            $this->store->put($key, $value);
        }

        foreach ($prune_updated_files as $prune) {
            Storage::disk($prune['disk'])->delete($prune['file']);
        }

        return response()->json($request_fields);
    }
}
