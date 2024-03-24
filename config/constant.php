<?php

return [
	'USER' => [
			'DEFAULT_IMAGE'   => '/images/user-profile.png',
		],
    'IMAGE_EXTENTIONS' => ['png','jpg','jpeg','gif'],

    'PER_PAGE_LIMIT' => 10,
	'DEFAULT_IMAGE'   => '/images/default.jpg',

    'USER_SAVE_IMAGE_PATH'   => '/uploads/profile-image',

    'MAIL_SETTING' => [
        'MAIL_MAILER' => env('MAIL_MAILER'),
        'MAIL_HOST' => env('MAIL_HOST'),
        'MAIL_PORT' => env('MAIL_PORT'),
        'MAIL_ENCRYPTION' => env('MAIL_ENCRYPTION'),
        'MAIL_USERNAME' => env('MAIL_USERNAME'),
        'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
        'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
    ],

    'CONFIGURATION' => [
        'APP_NAME' => env('APP_NAME'),
        'APP_SLOGUN' => env('App_SLOGUN')
    ],

    'ONESIGNAL' => [
        'ONESIGNAL_API_KEY' => env('ONESIGNAL_API_KEY'),
        'ONESIGNAL_REST_API_KEY' => env('ONESIGNAL_REST_API_KEY'),
        'CHANNEL_ID' => env('ONESIGNAL_CHANNEL_ID'),
        'ONESIGNAL_APP_ID_PROVIDER' => env('ONESIGNAL_APP_ID_PROVIDER'),
        'ONESIGNAL_REST_API_KEY_PROVIDER' => env('ONESIGNAL_REST_API_KEY_PROVIDER'),
        'ONESIGNAL_CHANNEL_ID_PROVIDER' => env('ONESIGNAL_CHANNEL_ID_PROVIDER'),
    ],

    'MAIL_PLACEHOLDER' => [
        'MAIL_MAILER' => 'smtp',
        'MAIL_HOST' => 'smtp.gmail.com',
        'MAIL_PORT' => '587',
        'MAIL_ENCRYPTION' => 'tls',
        'MAIL_USERNAME' => 'youremail@gmail.com',
        'MAIL_PASSWORD' => 'Password',
        'MAIL_FROM_ADDRESS' => 'youremail@gmail.com',
        'MAIL_FROM_NAME' => env('APP_NAME')
    ],
    'SUBSCRIPTION_STATUS' =>[
        'PENDING' => 'pending',
        'ACTIVE' => 'active',
        'INACTIVE' => 'inactive',
    ],
    'GOOGLE_MAP_KEY' =>[
        'GOOGLE_MAP_KEY' => '',
        'LATITUDE' => '',
        'LONGTITUDE' => '',
    ],
    'POST_STATUS' =>[
        'REQUESTED' => 'requested',
        'CANCELLED' => 'cancelled',
    ],
    'PAYMENT_HISTORY_ACTION' =>[
        'HANDYMAN_APPROVED_CASH' => 'handyman_approved_cash',
        'HANDYMAN_SEND_PROVIDER' => 'handyman_send_provider',
        'PROVIDER_APPROVED_CASH' => 'provider_approved_cash',
        'PROVIDER_SEND_ADMIN' => 'provider_send_admin',
        'ADMIN_APPROVED_CASH' => 'admin_approved_cash',
    ],
    'PAYMENT_HISTORY_STATUS' =>[
        'APPRVOED_HANDYMAN' => 'approved_by_handyman',
        'PENDING_PROVIDER' => 'pending_by_provider',
        'APPROVED_PROVIDER' => 'approved_by_provider',
        'PENDING_ADMIN' => 'pending_by_admin',
        'APPROVED_ADMIN' => 'approved_by_admin',
        'SEND_PROVIDER' => 'send_to_provider',
        'SEND_ADMIN' => 'send_to_admin'
    ],
];
