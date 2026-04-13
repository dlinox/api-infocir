<?php

return [

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => env('APP_TIMEZONE', 'America/Lima'),

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    'modules' => [
        'core' => [
            'levels' => [0, 1],
            'origins' => array_filter(array_map('trim', explode(',', env('APP_CORE_ORIGINS', '')))),
        ],
        'dairy' => [
            'levels' => [2],
            'origins' => array_filter(array_map('trim', explode(',', env('APP_DAIRY_ORIGINS', '')))),
        ],
        'training' => [
            'levels' => [0, 3],
            'origins' => array_filter(array_map('trim', explode(',', env('APP_TRAINING_ORIGINS', '')))),
        ],
    ],

];
