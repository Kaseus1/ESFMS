<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'xendit'),

    'xendit' => [
        'base_url' => env('XENDIT_BASE_URL', 'https://api.xendit.co'),
        'public_key' => env('XENDIT_PUBLIC_KEY'),
        'secret_key' => env('XENDIT_SECRET_KEY'),
        'webhook_secret' => env('XENDIT_WEBHOOK_SECRET'),
    ],

    
];