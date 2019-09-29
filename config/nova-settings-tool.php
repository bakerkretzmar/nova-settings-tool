<?php

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
    | Settings
    |--------------------------------------------------------------------------
    |
    | The good stuff :). Each setting defined here will render a field in the
    | tool. The only required key is `key`, other available keys include `type`,
    | `label`, `help`, `placeholder`, `language`, and `panel`.
    |
    */

    'settings' => [

        [
            'key' => 'facebook_url',
            'name' => 'Facebook Page',
            'help' => 'Company Facebook page.',
            'panel' => 'Social',
        ],

        [
            'key' => 'twitter_url',
            'name' => 'Twitter Profile',
            'panel' => 'Social',
        ],

        [
            'key' => 'feature_42',
            'name' => 'Feature 42',
            'type' => 'toggle',
            'help' => 'Upcoming release. <a href="/docs#feature_42">Read more here.</a>',
        ],

        [
            'key' => 'welcome',
            'name' => 'Welcome Message',
            'type' => 'textarea',
            'help' => 'Greeting for new users on their first login.',
        ],

        [
            'key' => 'snippet',
            'name' => 'Tracking Snippet',
            'type' => 'code',
            'language' => 'htmlmixed',
            'help' => 'Analytics snippet to add to all marketing pages.',
        ],

    ],

];
