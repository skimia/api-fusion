---
title: 'Créér et configurer une nouvelle ressource'
---

# Créér et configurer une nouvelle ressource

```
app/Domain/{ResourceName}
--> {ResourceName}.php // resource model eloquent
--> {ResourceName}Service.php // resource service
--> {ResourceName}Transformer.php // resource transformer
--> {ResourceName}Validator.php // resource validator

app/Http/Controllers/Api/v1
--> {ResourceName}Controller.php // resource controller
```
> ceci est juste une idée d'une bonne pratique à adopter pour classer ses resources api


## Création du model Eloquent

model eloquent classique étendant de la superClasse `Skimia\ApiFusion\Domain\ResourceServiceModel` (simple classe étendant de Eloquent et implémentant l'interface `Skimia\ApiFusion\Domain\Contracts\ResourceServiceModelContract`)
> Seule l'interface est requise (pour permettre de délivrer autre chose que des models BDD)

> Attention touts les champs modifiables grâce à l'API doivent être dans l'attribut `$fillable`

#### Exemple de model 

```php
namespace App\Domain\Packages;

use Skimia\ApiFusion\Domain\ResourceServiceModel;

class Package extends ResourceServiceModel {

    protected $fillable = ['name','description'];
}
```

## Création du validator

les validateur utilisent le package [fadion/validator-assistant](https://github.com/fadion/ValidatorAssistant) 
pour plus d'infos pour ecrire les validators veuillez consulter sa [documentation](https://github.com/fadion/ValidatorAssistant/blob/master/README.md)

#### Exemple de validator 

```php
namespace App\Domain\Packages;

use Skimia\ApiFusion\Domain\Contracts\InputValidatorContract;
use Fadion\ValidatorAssistant\ValidatorAssistant;

class PackageValidator extends ValidatorAssistant implements InputValidatorContract{

    protected $rules = [
        'name' => 'required|noWhitespace|alnum:-/|length:5,25|unique:packages,name,{id}',
        'description' => 'required|length:10'
    ];

    protected $messages = [
        'name.required' => 'Package Name is Required',
    ];
}
```
## Création du transformer

## Creation du service

## Creation du controller

## Commande de génération