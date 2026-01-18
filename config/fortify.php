<?php

use Laravel\Fortify\Features;

return [

    'guard' => 'web',

    'passwords' => 'users',

    'username' => 'email',

    'email' => 'email',

    'lowercase_usernames' => true,

    'home' => '/dashboard',

    'prefix' => '',

    'domain' => null,

    'middleware' => ['web'],

    'limiters' => [
        'login' => 'login',
        'two-factor' => 'two-factor',
    ],

    'views' => true,

    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        // Remove emailVerification since it's Jetstream-specific
        // Features::emailVerification(),

        // Remove updateProfileInformation() and updatePasswords() as those are Jetstream features
        // Features::updateProfileInformation(),
        // Features::updatePasswords(),

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]),
    ],

];
