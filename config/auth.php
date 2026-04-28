<?php

// Tambahkan konfigurasi ini ke config/auth.php
// Bagian 'guards' dan 'providers'

return [

    'defaults' => [
        'guard' => 'anggota',
        'passwords' => 'anggota',
    ],

    'guards' => [
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // ✅ Guard khusus untuk anggota
        'anggota' => [
            'driver'   => 'session',
            'provider' => 'anggota',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // ✅ Provider untuk anggota
        'anggota' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Anggota::class,
        ],
    ],

    'passwords' => [
        'anggota' => [
            'provider' => 'anggota',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
