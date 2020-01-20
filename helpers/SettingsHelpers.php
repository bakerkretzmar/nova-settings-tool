<?php
use Spatie\Valuestore\Valuestore;
use Illuminate\Support\Facades\Storage;

if(!function_exists('get_store_config_panels'))
{
    $loaded_panels = null;

    /**
     * Get all the store config panels.
     *
     * @return collect
     */
    function get_store_config_panels()
    {
        global $loaded_panels;
        if($loaded_panels){
            return $loaded_panels;
        }

        $panels = collect(config('nova-settings-tool.panels'));

        $loaded_panels = $panels->map(function ($panel) {
            $panel['fields'] = array_map(function ($field) use ($panel) {
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
            },array_filter(
                is_array($panel['fields']) ? $panel['fields'] : $panel['fields'](request()),
                function($field){
                    return true;
                }
            ));

            return $panel;
        });
        return $loaded_panels;
    }
}
if(!function_exists('get_store_config_fields'))
{
    $loaded_fields = null;

    /**
     * Get all the store config fields.
     *
     * @return collect
     */
    function get_store_config_fields()
    {
        global $loaded_fields;

        if ($loaded_fields) {
            return $loaded_fields;
        }

        $panels = get_store_config_panels();

        $loaded_fields = $panels->pluck('fields')->flatten();

        return $loaded_fields;
    }
}

if(!function_exists('get_store_config_field'))
{
    /**
     * Get store config field by the attribute.
     *
     * @param  string $attribute The attribute set in the field.
     *
     * @return Laravel\Nova\Fields\Field?
     */
    function get_store_config_field(string $attribute)
    {
        $fields = get_store_config_fields();

        return $fields->where('attribute', $attribute)->first();
    }
}

if(!function_exists('get_store_config'))
{
    /**
     * Get store config value
     *
     * @param  string $key The key of the stored config
     *
     * @return mix
     */
    function get_store_config(string $key)
    {
        $config_store = Valuestore::make(config('nova-settings-tool.path', storage_path('app/settings.json')));
        $config_value = $config_store->get($key);
        $field = get_store_config_field($key);

        if($field->component == 'file-field'){
            $config_value = Storage::disk($field->getStorageDisk())->url($config_value);
        } else {
            $temp_object = (object)[
                $key => $config_value
            ];

            $field->resolveForDisplay($temp_object, $key);
            $config_value = $field->value;
        }

        return $config_value;
    }
}

if(!function_exists('get_store_configs'))
{
    /**
     * Get store configs value
     *
     * @param  array $keys The keys array of the stored configs
     *
     * @return object
     */
    function get_store_configs(array $keys = [])
    {
        $values = [];

        foreach($keys as $key) {
            $values[$key] = get_store_config($key);
        }

        return (object)$values;
    }
}



