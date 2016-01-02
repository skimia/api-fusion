---
title: 'Installer & configurer les JSONWebToken'
---

# Installer & configurer les JSONWebToken

## Installation

Install jwt-auth with [Composer](http://getcomposer.org/doc/00-intro.md):

```json
composer require tymon/jwt-auth:0.5.*
```

Open `config/app.php` and register the required service provider above your application providers.
```php
'providers' => [
    Skimia\ApiFusion\ApiFusionServiceProvider::class
]
```
## Configuration