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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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


    'minutes_generator' => [
        'auth_username' => env('MINUTES_GENERATOR_AUTH_USERNAME'),
        'auth_password' => env('MINUTES_GENERATOR_AUTH_PASSWORD'),
    ],

    'ai' => [
        'provider' => env('AI_PROVIDER', 'nvidia'),
        'api_key' => env('AI_API_KEY'),
        'model' => env('AI_MODEL', 'meta/llama-3.1-70b-instruct'),
        'base_url' => env('AI_BASE_URL', 'https://integrate.api.nvidia.com/v1'),
        'max_transcript_length' => (int) env('AI_MAX_TRANSCRIPT_LENGTH', 20000),
    ],

];
