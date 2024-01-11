<?php

return [

    'screen' => [

        /*
         |--------------------------------------------------------------------------
         | Auto-discover default screen or not
         |--------------------------------------------------------------------------
         |
         | Should be Screen discovered with provided settings (e.g. name, url, etc) or not.
         | If set to `false` you need to register Screen manually within `routes/platform.php` file
         | Useful when you need to override conflicting names, slugs or add an extra middleware to a route
         |
         */

        'discover' => true,

        /*
         |--------------------------------------------------------------------------
         | Orchid permissions
         |--------------------------------------------------------------------------
         |
         | Permissions required from authenticated user in order to access log screen
         | Accepts string, array of strings or null. If you need more complexity you still can change
         | default screen itself and write own permissions there
         |
         | Default: "null" - everyone can access. Consider to change it
         */

        'permissions' => null,

        /*
         |--------------------------------------------------------------------------
         | Screen route name
         |--------------------------------------------------------------------------
         |
         | Route for log screen will be registered under this name
         |
         */

        'route' => 'platform.logs',
    ],

    /*
     |--------------------------------------------------------------------------
     | Default file
     |--------------------------------------------------------------------------
     |
     | Name of the default file to be selected. If filters are disabled this file will be selected by default
     | to show only its logs. Useful when log channel set to stack
     |
     */

    'default' => 'laravel.log',

    /*
     |--------------------------------------------------------------------------
     | Filters
     |--------------------------------------------------------------------------
     |
     | Manage storage logs filters
     |
     */

    'filters' => [
        /*
         |--------------------------------------------------------------------------
         | Disable filters completely
         |--------------------------------------------------------------------------
         |
         */

        'enabled' => true,

        /*
         |--------------------------------------------------------------------------
         | Filter keys
         |--------------------------------------------------------------------------
         |
         | If you encounter some issues or conflicts with the name of used filter keys,
         | you can change them here
         */

        'fileKey' => 'file',
        'levelKey' => 'level',

        /*
         |--------------------------------------------------------------------------
         | Exclude log files from filters
         |--------------------------------------------------------------------------
         |
         | If you wish to exclude specific log files from filter, pass these files here.
         | You can pass plain log file name or regex
         |
         */
        'exclude' => [
            // 'worker.log', // exclude specific file by name
            // '/^laravel-(?!2024)\d+-\d{2}-\d{2}\.log$/', // exclude all logs for year older than 2024 with "daily" channel
        ],
    ],

    'menu' => [
        /*
         |--------------------------------------------------------------------------
         | Register menu automatically in a sidebar or not
         |--------------------------------------------------------------------------
         |
         | If you encounter some issues or conflicts with the name of used filter keys,
         | you can change them here
         */

        'register' => true,
    ],
];
