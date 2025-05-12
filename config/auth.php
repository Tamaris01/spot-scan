<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'pengelola', // Pastikan ini sesuai dengan guard yang ingin digunakan
        'passwords' => 'pengelola', // Harus menggunakan 'passwords', bukan 'password'
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Here you may define every authentication guard for your application.
    | A great default configuration has been defined for you.
    |
    */

    'guards' => [
        'pengguna' => [
            'driver' => 'session',
            'provider' => 'pengguna',
        ],
        'pengelola' => [
            'driver' => 'session',
            'provider' => 'pengelola',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database.
    |
    */

    'providers' => [
        'pengguna' => [
            'driver' => 'eloquent',
            'model' => App\Models\PenggunaParkir::class, // Pastikan model ini ada
        ],
        'pengelola' => [
            'driver' => 'eloquent',
            'model' => App\Models\PengelolaParkir::class, // Pastikan model ini ada
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations for different user types.
    |
    */

    'passwords' => [
        'pengguna' => [
            'provider' => 'pengguna',
            'table' => 'password_reset_tokens', // Pastikan tabel ini ada
            'expire' => 60,
            'throttle' => 60,
        ],
        'pengelola' => [
            'provider' => 'pengelola',
            'table' => 'password_reset_tokens', // Pastikan tabel ini ada
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password.
    |
    */

    'password_timeout' => 10800,

];
