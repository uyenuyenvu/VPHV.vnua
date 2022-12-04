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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_APP_ID', '1027201556468-ba4hrdbpane0nvmm2gk12n8do0ollub7.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_APP_SECRET', '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'),
        'redirect' => env('GOOGLE_APP_CALLBACK_URL'),
    ],

    'microsoft' => [
        'client_id' => env('MICROSOFT_APP_ID'),
        'client_secret' => env('MICROSOFT_APP_SECRET'),
        'redirect' => env('MICROSOFT_APP_CALLBACK_URL'),
    ],
];
