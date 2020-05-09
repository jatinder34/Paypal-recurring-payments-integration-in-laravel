<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'paypal' => [
        'id'     => env('PAYPAL_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'url'    => [
            'redirect' => 'http://127.0.0.1:8000/excute-payment',
            'cancel' => 'http://127.0.0.1:8000/cancel',
            'executeAgreement' => [
                'success' => 'http://localhost:3001/excute-agreement?status=true',
                'failure' => 'http://localhost:3001/excute-agreement?status=false'
            ]
        ] 
    ],
];
