<?php

return [
    'screen' => [
        'name' => 'Logs',
        'description' => 'Manage app storage logs',
    ],

    'layout' => [
        'level' => 'Level',
        'text' => 'Log text',
        'stack' => 'Stack trace',
        'date' => 'Date',
    ],

    'filter' => [
        'file' => 'File',
        'level' => 'Level',

        'headings' => [
            'file' => 'Select log file',
            'level' => 'Choose log level',
        ],
    ],

    'actions' => [
        'clear' => [
            'btn_label' => 'Clear :file',
            'confirm_message' => 'All logs will be removed from :file file',
            'success_message' => 'File :file was cleared',
        ],

        'delete' => [
            'btn_label' => 'Delete :file',
            'confirm_message' => 'File :file will be removed',
            'success_message' => 'File :file was removed',
        ],
    ],
];
