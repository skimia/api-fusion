---
title: 'Installer & configurer les JSONWebToken'
---

# Installer & configurer les JSONWebToken

## Installation

Install jwt-auth with [Composer](http://getcomposer.org/doc/00-intro.md):

```json
composer require tymon/jwt-auth:0.6.*@dev
```


## Configuration

for use jwt login you must configure the sentinel model to a class implements `Tymon\JWTAuth\Contracts\JWTSubject` interface

you can use the built in class, in the `config/cartalyst.sentinel.php` configuraton file of the sentinel package

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    |
    | Please provide the user model used in Sentinel.
    |
    */

    'users' => [

        'model' => 'Skimia\ApiFusion\Auth\User',

    ],
];
```

you must configure the jwt-auth package by publish this configuration

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

//make sure to run for generate secret
php artisan jwt:secret
```

let's configure the auth provider for use sentinel user system with `jwt-auth`
by configuring the package config file to use the sentinel auth provider

```php
return [
        /*
        |--------------------------------------------------------------------------
        | Authentication Provider
        |--------------------------------------------------------------------------
        |
        | Specify the provider that is used to authenticate users.
        |
        */

        'auth' => Tymon\JWTAuth\Providers\Auth\Sentinel::class,
];
```

### Note for `This cache store does not support tagging.` Error

if you use a cache driver that not support tagging you have ann error with `5.1.28` version of laravel downgrade to `5.1.27` to work

### Note to Apache users


Apache seems to discard the Authorization header if it is not a base64 encoded user/pass combo. So to fix this you can add the following to your apache config

```apache_conf
RewriteEngine On
#Apache authorization headers
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```

## Usage

### connexion

for authenticate your request you must send the token with the correct header but for take your token you must login to your API

is the same as the http method but that return the token instead of the user class.

with this token you can signate your request by 2 header =>

```
sheild: bearer
authorization: bearer {your token here}
```
> you can retrive user with the correct header by calling the `/user` endpoint