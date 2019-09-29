<?php

return [

    'sidebar-label' => 'Configuration',

    'settings' => [

        [
            'type' => 'text',
            'key' => 'facebook_url',
            'label' => 'Facebook',
            'help' => 'App Twitter page URL. <a href="#">Read more.</a>',
            'default' => 'jacobbakr',
        ],

        [
            'key' => 'test_setting',
        ],

        [
            'key' => 'setting_with_no_metadata',
        ],

    ],

    'panels' => [

        [

            'name' => 'Settings',

            'settings' => [

                [
                    'key' => 'facebook_url',
                    'name' => 'Facebook',
                    'type' => 'text',
                    'description' => 'App Facebook page URL.',
                    'link' => [
                        'text' => 'More.',
                        'url' => '/documentation#facebook_url',
                    ],
                ],

                [
                    'key' => 'test_string',
                    'name' => 'Test String',
                    'type' => 'text',
                    'description' => 'Test string.',
                    'link' => [
                        'text' => 'More.',
                        'url' => '/documentation#twitter_url',
                    ],
                ],

            ],

        ],

        [

            'name' => 'Features',

            'settings' => [

                [
                    'key' => 'new_feature',
                    'name' => 'New Feature',
                    'type' => 'toggle',
                    'description' => 'Top secret new app feature.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

                [
                    'key' => 'enabled_feature',
                    'name' => 'Enabled Feature',
                    'type' => 'toggle',
                    'default' => true,
                    'description' => 'Feature enabled by default.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

                [
                    'key' => 'welcome_message',
                    'name' => 'Welcome Message',
                    'type' => 'textarea',
                    'description' => 'Message for new users on their first login.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

                [
                    'key' => 'snippet',
                    'name' => 'Code Snippet',
                    'type' => 'code',
                    'language' => 'javascript',
                    'description' => 'Code to inject into the homepage.',
                    'link' => [
                        'text' => 'Documentation',
                        'url' => '/documentation#new_feature',
                    ],
                ],

            ],

        ],

    ],

];
