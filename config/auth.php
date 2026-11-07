<?php

use App\Models\Admin;
use App\Models\Instructor;
use App\Models\Student;

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | The storefront-facing guard is `student` — the /login and /register
    | pages belong to it. Each panel has its own guard, model, table and
    | session, so admin / instructor / student login states are independent.
    |
    */

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'student'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'students'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Supported: "session"
    |
    */

    'guards' => [
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'instructor' => [
            'driver' => 'session',
            'provider' => 'instructors',
        ],
        'student' => [
            'driver' => 'session',
            'provider' => 'students',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | One eloquent provider per user table: admins, instructors, students.
    |
    | Supported: "database", "eloquent"
    |
    */

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model' => Admin::class,
        ],
        'instructors' => [
            'driver' => 'eloquent',
            'model' => Instructor::class,
        ],
        'students' => [
            'driver' => 'eloquent',
            'model' => Student::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | All three panels share the password_reset_tokens table; each broker
    | scopes by provider so tokens never cross user types.
    |
    */

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'instructors' => [
            'provider' => 'instructors',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'students' => [
            'provider' => 'students',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
