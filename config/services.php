<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'electric' => [
        'url' => env('ELECTRIC_URL', 'http://electric:3000/v1/shape'),
        'secret' => env('ELECTRIC_SECRET', ''),
        'timeout' => env('ELECTRIC_TIMEOUT', 30),
    ],

    'flyff' => [
        'url' => env('FLYFF_URL', 'https://api.flyff.com'),
        'timeout' => env('FLYFF_TIMEOUT', 30),
        'chunk' => env('FLYFF_SCRAPING_CHUNK', 100),
        'item_endpoint' => env('FLYFF_ITEM_ENDPOINT', '/item'),
        'class_endpoint' => env('FLYFF_CLASS_ENDPOINT', '/class'),
    ],

];
