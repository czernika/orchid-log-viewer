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
     | Layout data
     |--------------------------------------------------------------------------
     |
     | Information related to a table
     |
     */
    'table' => [
        /**
         * How many records to show per page
         */
        'per_page' => 15,

        /**
         * Pagination key name
         */
        'page_name' => 'page',

        /**
         * Params for DateTimeSplit component
         */
        'date_column_params' => [
            'upperFormat' => 'M j, Y',
            'lowerFormat' => 'D, H:i',
            'tz' => null,
        ],

        /**
         * Text column width
         * 
         * Text column which contains information about log message can be huge
         * It is better to define its width. Accept integers (pixel values)
         * or any valid CSS size value (e.g, '70%')
         */
        'text_column_width' => 700, // px

        'stack' => [
            /**
             * Show modal toggle to view stack trace or not. It may throw 414 error which
             * may be annoying for end-users, so you may want to disable it or shorten
             */
            'render' => true,

            /**
             * Limit stack trace message
             *
             * Set "0" if you wish to keep full length trace
             */
            'limit' => 1000, // characters
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Filters
     |--------------------------------------------------------------------------
     |
     | Manage storage logs filters
     |
     */
    'filters' => [
        /**
         * Disable filters completely
         */
        'enabled' => [
            'level' => true,
            'file' => true,
        ],

         /**
          * Filter key names
          * 
          * If you encounter some issues or conflicts with the name of used filter keys you can change them here
          */
        'fileKey' => 'file',
        'levelKey' => 'level',

        /**
         * Exclude log files from filters
         *
         * If you wish to exclude specific log files from filter, pass these files here.
         * You can pass plain log file name or regular expression
         */
        'exclude' => [
            // 'worker.log', // exclude specific file by name
            // '/^laravel-(?!2024)\d+-\d{2}-\d{2}\.log$/', // exclude all logs for year older than 2024 with "daily" channel
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Sidebar dashboard menu
     |--------------------------------------------------------------------------
     */
    'menu' => [
        
        /**
         * Register or not to register - that is the question
         * If set to `false` you need to register it manually in PlatformProvider to add menu link to sidebar
         */
        'register' => true,
    ],
];
