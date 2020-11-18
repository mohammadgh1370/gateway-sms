<?php

return [
    /*
     * Available drivers:
     */

    'default' => env('SMS_DRIVER', 'farazsms'),

    'drivers' => [
        'farazsms' => [
            'url'      => env('FARAZSMS_URL', 'http://rest.ippanel.com'),
            'username' => env('FARAZSMS_USERNAME'),
            'password' => env('FARAZSMS_PASSWORD'),
            'from'     => env('FARAZSMS_FROM', '+983000505'),
            'api_key'  => env('FARAZSMS_API_KEY')
        ],
        'log'      => [],
    ],
];