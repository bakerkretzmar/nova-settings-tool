<?php
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Country;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Place;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Sparkline;
use Laravel\Nova\Fields\Status;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Timezone;
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
            'fields' => function(){
                return [
                    Text::make('Facebook Profile', 'facebook_url')
                        ->rules('required', 'url'),
                    Text::make('Twitter Profile', 'twitter_url'),
                    Text::make('Instagram Profile', 'instagram_url'),
                    Text::make('Linkedin Profile', 'linkedin_url'),
                    Image::make('Logo', 'logo'),
                ];
            }
        ],
        [
            'name' => 'Available Nova Fields',
            'fields' => function(){
                return [
                    Avatar::make('Avatar', 'avatar'),
                    Boolean::make('Active', 'active'),
                    Code::make('Code json', 'json_code')
                        ->json(),
                    Currency::make('Price', 'price')
                        ->format('%.2n'),
                    Country::make('Country', 'country'),
                    Date::make('Birthday', 'birthday'),
                    DateTime::make('Updated At', 'updated_at'),
                    File::make('Attachment', 'attachment'),
                    Heading::make('Meta'),
                    Image::make('Photo', 'photo'),
                    KeyValue::make('Meta', 'meta')
                        ->rules('json'),
                    Number::make('Price', 'number_price')
                        ->min(1)
                        ->max(1000)
                        ->step(0.01),
                    Password::make('Password', 'password'),
                    Place::make('Address', 'address_line_1')
                        ->countries(['DK', 'UK', 'US', 'CA', 'AU']),
                    Place::make('City', 'city')
                        ->countries(['DK', 'UK', 'US', 'CA', 'AU'])
                        ->onlyCities(),
                    Select::make('Size', 'size')
                        ->options([
                            'S' => 'Small',
                            'M' => 'Medium',
                            'L' => 'Large',
                        ]),
                    Text::make('Name', 'name'),
                    Textarea::make('Textarea', 'textarea'),
                    Trix::make('Trix', 'trix_editor'),
                    Markdown::make('Biography', 'biography'),
                    Timezone::make('Timezone', 'timezone'),

                    // Here is the detail related fields (only visible on detail view)
                    Sparkline::make('Post Views', 'post_views')
                        ->data([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]),
                    Status::make('Status', 'status')
                        ->loadingWhen(['waiting', 'running'])
                        ->failedWhen(['failed']),

                ];
            },
        ],
        [
            'name' => 'Other',
            'fields' => function(){
                return [
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
                ];
            },
        ]
    ],
];
