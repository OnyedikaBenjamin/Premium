<?php

return [
/*
    'PAYTM' => [
        'MERCHANT_ID' => '',
        'SECRET_KEY' => '',
        'CHANNEL_ID' => '',
        'WEBSITE' => '',
        'INDUSTRY_TYPE_ID' => '',
        'CALLBACK_URL' => '',
    ],
*/

    'CURRENCY' => [
        'COUNTRY_ID' => '',
        'POSITION' => ''
    ],

    'ONESIGNAL' => [
        'API_KEY' => env('ONESIGNAL_API_KEY'),
        'REST_API_KEY' => env('ONESIGNAL_REST_API_KEY'),
        'CHANNEL_ID' => env('ONESIGNAL_CHANNEL_ID'),
        'ONESIGNAL_APP_ID_PROVIDER' => env('ONESIGNAL_APP_ID_PROVIDER'),
        'ONESIGNAL_REST_API_KEY_PROVIDER' => env('ONESIGNAL_REST_API_KEY_PROVIDER'),
        'ONESIGNAL_CHANNEL_ID_PROVIDER' => env('ONESIGNAL_CHANNEL_ID_PROVIDER'),

    ],

    'DISTANCE' => [
        'TYPE' => '',
        'RADIOUS' => '',
    ],
    'MAPKEY' =>[
        'GOOGLE_MAP_KEY' => '',
        'LATITUDE' => '',
        'LONGTITUDE' => '',
    ]
]
?>