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
      "path": "/",
      "methods": [
        "GET",
        "HEAD"
      ]
    }
  ]
}
```

```php
RouteFusion::apiIndexListing($api);
```
