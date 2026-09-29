<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:5173', // Vite default
        'http://127.0.0.1:5173',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:8000', // Laravel server
        'http://127.0.0.1:8000',
        'http://localhost', // Allow localhost without port
        'http://127.0.0.1',
        'https://ilorinemirateyouths.com', // Production frontend
        'https://www.ilorinemirateyouths.com',
        'https://control.ilorinemirateyouths.com', // Production admin
        'https://www.control.ilorinemirateyouths.com', // Production admin with www
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/localhost(:[0-9]+)?$/',
        '/^http:\/\/127\.0\.0\.1(:[0-9]+)?$/',
        '/^https:\/\/(www\.)?ilorinemirateyouths\.com$/',
        '/^https:\/\/(www\.)?control\.ilorinemirateyouths\.com$/',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['*'],

    'max_age' => 0,

    'supports_credentials' => true,

];
