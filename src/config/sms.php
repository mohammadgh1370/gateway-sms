<?php

return [
    /*
     * Available drivers:
     */

    'default' => env('SMS_DRIVER', 'farazsms'),

    'drivers' => [
        'farazsms' => [
            'url'     => env('FARAZSMS_URL', 'http://rest.ippanel.com'),
            'from'    => env('FARAZSMS_FROM'),
            'api_key' => env('FARAZSMS_API_KEY'),
        ],

        'log' => [],
    ],
];