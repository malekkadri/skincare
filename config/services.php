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

    'xai' => [
        'api_key' => env('XAI_API_KEY'),
        'base_url' => env('XAI_BASE_URL', 'https://api.x.ai/v1'),
        'vision_model' => env('XAI_VISION_MODEL', 'grok-2-vision-latest'),
        'enabled' => env('XAI_FACE_ANALYSIS_ENABLED', true),
        'timeout' => env('XAI_TIMEOUT', 30),
        'connect_timeout' => env('XAI_CONNECT_TIMEOUT', 10),
        'max_output_chars' => env('XAI_MAX_OUTPUT_CHARS', 4000),
        'store' => env('XAI_STORE', false),
    ],

    'ai' => [
        'ssl_verify' => env('AI_SSL_VERIFY', true),
        'ca_bundle_path' => env('AI_CA_BUNDLE_PATH'),
    ],

];
