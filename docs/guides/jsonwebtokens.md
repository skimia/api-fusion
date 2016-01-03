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
return [/*
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
```

### Note to Apache users


Apache seems to discard the Authorization header if it is not a base64 encoded user/pass combo. So to fix this you can add the following to your apache config

```apache_conf
RewriteEngine On
#Apache authorization headers
RewriteCond %{HTTP:Authorization} ^(.*)
RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
```
