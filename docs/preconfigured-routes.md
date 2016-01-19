---
title: Routes Preconfigurées
---

# Routes préconfigurées

vous pouvez ajouter des fonctionnalitées simplement à votre api.

fichier `routes.php`

```php
APIRoute::version(
    'v1',
    function($api)
    {

        //injecter les routes préconfigurées ici

    });
```


## Index des resources de l'Api

fournis un listing des ressources de l'api sur une url definie (default '/')

```json
{
  "endpoints": [
    {
      "path": "/skimia.api.svc",
      "methods": [
        "GET",
        "HEAD"
      ]
    },
    {
      "path": "/skimia.api.svc/login",
      "methods": [
        "POST"
      ]
    },
    {
      "path": "/skimia.api.svc/user",
      "methods": [
        "GET",
        "HEAD"
      ]
    },
    {
      "path": "/skimia.api.svc/sessions",
      "methods": [
        "GET",
        "HEAD"
      ]
    },
    {
      "path": "/skimia.api.svc/sessions/kill",
      "methods": [
        "GET",
        "HEAD"
      ]
    },
    {
      "path": "/skimia.api.svc/sessions/kill/all",
      "methods": [
        "GET",
        "HEAD"
      ]
    },
    {
      "path": "/skimia.api.svc/sessions/kill/{code}",
      "methods": [
        "GET",
        "HEAD"
      ]
    },
    {
      "path": "/",
      "methods": [
        "GET",
        "HEAD"
      ]
    }
  ]
}
```
code à injecter
```php
RouteFusion::apiIndexListing($api);
```

## Routes d'authetification

utilise `cartalyst/Sentinel` pour la gestion utilisateurs, il faut l'installer.

pour ce faire il faut copier et executer les migrations :

> Avant d'utiliser les commandes suivantes, il faut supprimer les migrations par defaut de laravel pour eviter une colision sur les tables

```
php artisan vendor:publish --provider="Cartalyst\Sentinel\Laravel\SentinelServiceProvider"

php artisan migrate
```

code à injecter
```php
RouteFusion::apiLoginHttp($api); //authentification par http (cookies)
RouteFusion::apiLoginJWT($api); //authentification par JSONWebTokens (you must install tymon/jwt-auth package)
```
> This package makes use of a 3rd party package to integrate JWT authentication. Please refer to the [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth) GitHub page for details on installing and configuring the package

> Au moment d'ecrire ces docs le package est en train de subir des refactorisations bloquantes c'est pour cela que nous vous conseillons d'installer le package via les commandes suivantes
```
composer require tymon/jwt-auth=dev-develop#2f6a539
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret
```

route `/skimia.api.svc/login` [POST]

header `shield :sentinel` requis pour les routes protégées si on utilise la methode HTTP

header `shield :bearer`   requis pour les routes protégées si on utilise la methode JWT

données POST
```php
array(
  'email'       => 'john.doe@example.com',
  'password'    => 'foobar',
  'remember-me' => true //only for http auth
)
```
