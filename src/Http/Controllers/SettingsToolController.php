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

    /**
     * Endpoint for getting the setting panels with fields.
     *
     * @param  Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return response
     */
    public function fields(NovaRequest $request)
    {
        $values = $this->store->all();
        $panels = collect(config('nova-settings-tool.panels'));

        return response()->json(
            [
                'data' => [
                    'panels' => $panels->map(function ($panel) use ($values) {
                        $panel['fields'] = array_map(function ($field) use ($values) {
                            // Set the value into the field.
                            $field->value = $values[$field->attribute] ?? null;

                            // Set a default value else the code field will make a split error.
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

    /**
     * Endpoint for saving the settings in panels with fields.
     *
     * @param  Illuminate\Http\Request $request
     *
     * @return response
     */
    public function write(Request $request)
    {
        $values = $this->store->all();

        // Get all the panels in the config file.
        $panels = collect(config('nova-settings-tool.panels'));

        // Extract all the fields in the panels.
        $fields = $panels->pluck('fields')->flatten();
        // Get all the attributes out of the fields.
        $attributes = $fields->pluck('attribute');

        // Filter out all the keys which isn't in the config file.
        $request_fields = $request->only($attributes->toArray());

        // Array for cleaning up the images which has been updated.
        $prune_updated_files = [];

        // Loop through all the panels fields.
        foreach ($request_fields as $key => $value) {
            // Find the field by key:attribute in the $fields collection.
            $field = $fields->firstWhere('attribute', $key);

            // Check if the field is a file field
            if ($field->component == 'file-field') {
                // If the field doesn't have a file, then continue.
                if (!$request->hasFile($key)) {
                    continue;
                }

                // Extract all the field storage information.
                $dir = $field->getStorageDir();
                $disk = $field->getStorageDisk();
                $storage_dir = $dir ? $dir . '/' : '';

                // Save the file and update value with the filename.
                $value = $request->file($key)->store($dir, $disk);

                // Check if the store has a value already and if it exists.
                $last_value = $this->store->get($key);
                if ($last_value && Storage::disk($disk)->exists($storage_dir . $last_value)) {
                    $prune_updated_files[] = [
                        'disk' => $disk,
                        'file' => $storage_dir . $last_value,
                    ];
                }

                // Update the request field value for response.
                $request_fields[$key] = $value;
            }

            // Update the settings store.
            $this->store->put($key, $value);
        }

        // Loop through all the prune files which has to be deleted.
        foreach ($prune_updated_files as $prune) {
            Storage::disk($prune['disk'])->delete($prune['file']);
        }

        return response()->json($request_fields);
    }
}
