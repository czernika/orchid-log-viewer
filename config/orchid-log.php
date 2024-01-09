<?php

return [

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

    'screen' => [
        'discover' => true,
    ],
];
