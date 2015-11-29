---
title: Getting started
---

# Getting started with Skimia/ApiFusion

Welcome! This guide will help you get started with using Skimia/ApiFusion in your project.

## Requirement
due of the package dependencies in developpement tree you must configure in your `composer.json` file

```json
{
  "minimum-stability": "dev",
  "prefer-stable": true
}
```

## Installation

Install Skimia\\ApiFusion with [Composer]('http://getcomposer.org/doc/00-intro.md'):

```json
composer require skimia/api-fusion
```

Open `config/app.php` and register the required service provider above your application providers.
```php
'providers' => [
    Skimia\ApiFusion\ApiFusionServiceProvider::class
]
```
### Configuration

configuration utilisée par laravel

```ini
API_VENDOR=skimia
API_PREFIX=skimia.api.svc
API_VERSION=v1
API_DEBUG=true
```
