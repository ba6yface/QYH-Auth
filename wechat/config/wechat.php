<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    'corp_id' => env('CORP_ID', 'abc123'),

    'corp_key' => env('CORP_KEY', 'abc123'),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Specify the various providers used throughout the package.
    |
    */

    'providers' => [

        /*
        |--------------------------------------------------------------------------
        | User Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to find the user based
        | on the subject claim
        |
        */

        'user' => 'Dex\Wechat\Providers\User\EloquentUserAdapter',

        /*
        |--------------------------------------------------------------------------
        | JWT Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to create and decode the tokens.
        |
        */

        'jwt' => 'Tymon\JWTAuth\Providers\JWT\NamshiAdapter',

        /*
        |--------------------------------------------------------------------------
        | Authentication Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to authenticate users.
        |
        */

        'auth' => 'Tymon\JWTAuth\Providers\Auth\IlluminateAuthAdapter',

        /*
        |--------------------------------------------------------------------------
        | Storage Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to store tokens in the blacklist
        |
        */

        'storage' => 'Tymon\JWTAuth\Providers\Storage\IlluminateCacheAdapter',

    ],

];
