<?php

namespace Bakerkretzmar\NovaSettingsTool\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Valuestore\Valuestore;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Storage;
use App\Nova\Client as ClientResource;
use Illuminate\Support\Facades\Hash;
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

    private function get_setting_panels(NovaRequest $request, $values = [])
    {
        $panels = collect(config('nova-settings-tool.panels'));

        return $panels->map(function ($panel) use ($request, $values) {
            $panel['fields'] = array_map(function ($field) use ($panel, $values) {
                // Set the value into the field.
                $field->value = $values[$field->attribute] ?? null;
                $field->panel = $panel['name'];

                // Set a default value else the code field will make a split error.
                if($field->component == 'code-field'){
                    if(!$field->value) $field->value = '';
                }

                if($field->component == 'key-value-field' && $field->value && is_string($field->value)){
                    $field->value = json_decode($field->value);
                }

                // Remove delete option for files.
                if ($field->component == 'file-field') {
                    $field->deletable(false);
                }

                return $field;
                }, array_filter($panel['fields']($request), function($field){
                    return true;
                }));

            return $panel;
        });
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
        $panels = $this->get_setting_panels($request, $values);

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

    /**
     * Endpoint for saving the settings in panels with fields.
     *
     * @param  Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return response
     */
    public function write(NovaRequest $request)
    {
        $values = $this->store->all();

        // Get all the panels in the config file.
        $panels = $this->get_setting_panels($request, $values);

        // Extract all the fields in the panels.
        $fields = $panels->pluck('fields')->flatten();
        // Get all the attributes out of the fields.
        $attributes = $fields->pluck('attribute');
        $field_rules = [];

        $fields->each(function($field) use (&$field_rules){
            if($field->rules && count($field->rules) > 0){
                $field_rules[$field->attribute] = $field->rules;
            }
        });
        $request->validate($field_rules);

        // Filter out all the keys which isn't in the config file.
        $request_fields = $request->only($attributes->toArray());

        // Array for cleaning up the images which has been updated.
        $prune_updated_files = [];

        // Loop through all the panels fields.
        foreach ($request_fields as $key => $value) {
            // Find the field by key:attribute in the $fields collection.
            $field = $fields->firstWhere('attribute', $key);

            if($field->component == 'password-field'){// Check if the field is a Password field
                $field_value = Hash::make($value);
            } else {
                $temp_object = (object)[];
                // Let the Nova field resolver take care of the value resolver.
                $field->fill($request, $temp_object);

                // Get the Nova field resolvers value again.
                $field_value = $temp_object->{$key} ?? null;

                // Check if the field is a file field
                if ($field->component == 'file-field') {
                    // Check if the store has a value already and if it exists.
                    $last_value = $this->store->get($key);
                    // Extract all the field storage information.
                    $dir = $field->getStorageDir();
                    $disk = $field->getStorageDisk();
                    $storage_dir = $dir ? $dir . '/' : '';

                    if ($last_value && Storage::disk($disk)->exists($storage_dir . $last_value)) {
                        $prune_updated_files[] = [
                            'disk' => $disk,
                            'file' => $storage_dir . $last_value,
                        ];
                    }
                }
            }

            // Update the settings store.
            $this->store->put($key, $field_value);
            $request_fields[$key] = $field_value;
        }

        // Loop through all the prune files which has to be deleted.
        foreach ($prune_updated_files as $prune) {
            Storage::disk($prune['disk'])->delete($prune['file']);
        }

        return response()->json($request_fields);
    }
}
