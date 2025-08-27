<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Throttle Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the throttle configuration for rate limiting
    | various routes in the application.
    |
    */

    'login' => [
        'max_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
        'decay_minutes' => env('LOGIN_DECAY_MINUTES', 1),
    ],

    'api' => [
        'max_attempts' => env('API_MAX_ATTEMPTS', 60),
        'decay_minutes' => env('API_DECAY_MINUTES', 1),
    ],

    'email_verification' => [
        'max_attempts' => env('EMAIL_VERIFICATION_MAX_ATTEMPTS', 6),
        'decay_minutes' => env('EMAIL_VERIFICATION_DECAY_MINUTES', 1),
    ],

    'password_reset' => [
        'max_attempts' => env('PASSWORD_RESET_MAX_ATTEMPTS', 3),
        'decay_minutes' => env('PASSWORD_RESET_DECAY_MINUTES', 5),
    ],

    'admin_actions' => [
        'max_attempts' => env('ADMIN_ACTIONS_MAX_ATTEMPTS', 30),
        'decay_minutes' => env('ADMIN_ACTIONS_DECAY_MINUTES', 1),
    ],
];
