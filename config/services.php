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

    'recaptcha' => [
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    ],

    'rembg' => [
    'url' => env('REMBG_API_URL', 'http://172.16.0.247:5000/api'),
    ],
    // Agregar configuración del microservicio NestJS:
    'nestjs' => [
        'url' => env('SERVICES_NESTJS_URL', 'http://localhost:3000/api'),
    ],
];
