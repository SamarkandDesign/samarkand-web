<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => '',
    ],

    'ses' => [
        'key'    => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'stripe' => [
        'model'       => 'User',
        'publishable' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret'      => env('STRIPE_SECRET_KEY'),
    ],

    'google' => [
        'ga_tracking_id'         => env('GA_TRACKING_ID'),
        'server_key'             => env('GOOGLE_SERVER_KEY'),
        'browser_key'            => env('GOOGLE_BROWSER_KEY'),
    ],

    'xero' => [
        'key'     => env('XERO_KEY'),
        'secret'  => env('XERO_SECRET'),
        // The contents of the .pem file should be base64 encoded and stored as an environment variable
        'rsa_key' => env('XERO_RSA_KEY') ? base64_decode(env('XERO_RSA_KEY')) : '',

        'invoice_account' => env('XERO_INVOICE_ACCOUNT', '200'),
        'payment_account' => env('XERO_PAYMENT_ACCOUNT', '090'),

    ],
    
    'telegram-bot-api' => [
        'token' => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE')
    ],
];
