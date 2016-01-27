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

## Creation du service

le resourceService est le scotch entre le model eloquent, son validator ( et ses filtres ), ainsi que toutes les régles de sécurité
c'est le manager sécurisé du model eloquent.

#### Exemple de service

```php
namespace App\Domain\Packages;

use Skimia\ApiFusion\Domain\ResourceService;

class PackageService extends ResourceService{

    /**
     * Set the resource's model and validator
     */
    public function __construct()
    {
        parent::__construct(
            new Package, //le model
            new PackageValidator //sa classe de validation
        );
    }

    protected function readAuthorised()
    {
        return true;//autorise tout le monde à lire une ou la liste des resources
    }

    protected function storeAuthorised()
    {
        return true;//autorise tout le monde à ajouter/modfier une resource
    }

    protected function destroyAuthorised()
    {
        return true; //autorise tout le monde à supprimer une resource
    }


}
``` 

## Création du transformer

les transformer utilisent le package [league/fractal](http://fractal.thephpleague.com/) 
pour plus d'infos pour ecrire les classes transformer veuillez consulter sa [documentation](http://fractal.thephpleague.com/transformers/#classes-for-transformers)

#### Exemple de transformer 

```php
namespace App\Domain\Packages;

use League\Fractal\TransformerAbstract;


class PackageTransformer extends TransformerAbstract{

    /**
     * Transform resource into standard output format with correct typing
     * @param Package $package  Resource being transformed
     * @return array              Transformed object array ready for output
     */
    public function transform(Package $package)
    {
        return [
            'id'			=> (int) $package->id,
            'name'			=> $package->name,
            'description'	=> $package->description,
            'links'			=> [
                [
                    'rel' => 'self',
                    'uri' => (url('skimia.api.svc').'/packages/'. $package->id),//method url à changer pour avoir le veritable skimia.api.svc en fonction de la configuration au lieu de le mettre en brut
                ]
            ],
        ];
    }
}
```

## Creation du controller

le resourceController est le scotch entre le service, sa représentation ( son transformer ), ainsi que le lien avec le router

#### Exemple de controller API

```php
namespace App\Http\Controllers\Api\v1;


use App\Domain\Packages\PackageService;
use App\Domain\Packages\PackageTransformer;
use Skimia\ApiFusion\Http\Controllers\Api\ResourceServiceController;

//utilisation de l'annotation @ApiResource pour lier le controller au router d'api
/**
 * Class PackagesController
 * @package App\Http\Controllers\Api\v1
 *
 * @ApiResource("packages", version="v1" , except={"create", "edit"})
 *
 */
class PackagesController extends ResourceServiceController{

    /**
     * Set the service and transformer classes
     */
    public function __construct()
    {
        parent::__construct();
        $this->service = new PackageService;
        $this->transformer = new PackageTransformer;
    }
}
```
## Commande de génération

heyhey