<?php

return [
    'default' => 'file',

    'temp_dir' => sys_get_temp_dir(),

    'drivers' => [
        'file' => [
            'storage' => storage_path('imagestorage'),
        ],
    ],
];