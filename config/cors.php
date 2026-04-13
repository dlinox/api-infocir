<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_filter(array_map('trim', array_merge(
        explode(',', env('APP_CORE_ORIGINS', '')),
        explode(',', env('APP_DAIRY_ORIGINS', '')),
        explode(',', env('APP_TRAINING_ORIGINS', '')),
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
