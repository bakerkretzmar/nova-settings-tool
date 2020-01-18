<?php
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Trix;

return [

    /*
    |--------------------------------------------------------------------------
    | Settings Path
    |--------------------------------------------------------------------------
    |
    | Path to the JSON file where settings are stored.
    |
    */
    'path' => storage_path('app/settings.json'),

    /*
    |--------------------------------------------------------------------------
    | Sidebar Label
    |--------------------------------------------------------------------------
    |
    | The text that Nova displays for this tool in the navigation sidebar.
    |
    */
    'sidebar-label' => 'Settings',

    /*
    |--------------------------------------------------------------------------
    | Panel visualization
    |--------------------------------------------------------------------------
    |
    | How should the settings panels be visualized: 'accordion' or 'stacked'?
    | Stacked will show the panels in a normal resource behavior.
    | Accordion will show a sidebar which will switch between the settings.
    |
    */
    'panel-visualized' => 'accordion',

    /*
    |--------------------------------------------------------------------------
    | Accordion active remember
    |--------------------------------------------------------------------------
    |
    | If you want the accordion sidebar to remember which panel had active the last
    | time, then set this config to true.
    |
    */
    'accordion-active-remember' => false,

    /*
    |--------------------------------------------------------------------------
    | Panels
    |--------------------------------------------------------------------------
    |
    | The good stuff :). Each panel defined here will render a panel in the
    | tool. The required keys for a panel is 'name':string and 'fields':array.
    | Inside the 'fields' array you can add all the nice Nova fields and use them
    | like you are used to, BUT you can't use relationship fields, because there
    | is nothing to relate to.
    |
    */
    'panels' => [
        [
            'name' => 'Social',
            'fields' => [
                Text::make('Twitter Profile', 'twitter_url'),
            ]
        ],
        [
            'name' => 'Other',
            'fields' => [
                Boolean::make('Feature 42', 'feature_42')
                    ->help('For the upcoming release. <a href="/docs#feature_42">Read more here.</a>'),
                Textarea::make('Welcome Message', 'welcome')
                    ->help('Greeting for new users on their first login.'),
                Code::make('Tracking Snippet', 'snippet')
                    ->language('html')
                    ->help('Analytics snippet to add to all marketing pages.'),
                Select::make('Default App Theme', 'theme')
                    ->options([
                        'dark' => 'Dark theme',
                        'light' => 'Light theme'
                    ]),
                Number::make('Timeout (min.)', 'timeout'),
                Trix::make('Longer presentation', 'long_text'),
            ]
        ]
    ],
];
