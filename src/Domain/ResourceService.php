<?php
/**
 * Created by PhpStorm.
 * User: Jean-françois
 * Date: 22/08/2015
 * Time: 10:10.
 */
namespace Skimia\ApiFusion\Domain;

use Skimia\ApiFusion\Domain\Users\SentinelServiceUserAdapter;
use Illuminate\Database\Eloquent\Collection;
use Skimia\ApiFusion\Domain\Contracts\InputValidatorContract;
use Skimia\ApiFusion\Domain\Contracts\ServiceUserContract;
use Skimia\ApiFusion\Domain\Contracts\ResourceServiceModelContract;
use Skimia\ApiFusion\Domain\Traits\CheckableTrait;
use Skimia\ApiFusion\Domain\Exceptions\DomainException;

abstract class ResourceService
{
    use CheckableTrait;

    /**
     * The resource's model object that the service will use.
     * @var ResourceServiceModelContract
     */
    protected $model;

    /**
     * User of the service.
     * @var ServiceUserContract
     */
    protected $user;

    /**
     * Field to order results by.
     * @var array
     */
    protected $orderBy = ['created_at'];

    /**
     * Related resources to eager load.
     * @var array
     */
    protected $eagerLoad = [];

    /**
     * Set the resource's model and validator.
     * @param ResourceServiceModelContract            $model           Resource's model
     * @param InputValidatorContract          $inputValidator  Resource's input validator
     */
    public function __construct(
        ResourceServiceModelContract $model,
        InputValidatorContract $inputValidator = null
    ) {
        $this->model = $model;
        $this->inputValidator = $inputValidator;
        $this->user = new SentinelServiceUserAdapter(\Sentinel::getUser()); // TODO: extract out
    }

    /*
    |--------------------------------------------------------------------------
    | Easy Method to manipulate Query
    |--------------------------------------------------------------------------
    */

    /**
     * Apply order by property to query.
     */
    protected function order()
    {
        foreach ($this->orderBy as $orderBy) {
            if (count($orderBy) == 1) {
                $this->model = $this->model->orderBy($orderBy);
            }

            // field and direction
            if (count($orderBy) == 2) {
                $this->model = $this->model->orderBy($orderBy[0], $orderBy[1]);
            }
        }
    }

    /**
     * Run query including eager load.
     * @param  int                 $id   Item ID
     * @return ResourceServiceModelContract|Collection    Query results
     */
    protected function get($id = null)
    {
        $this->filter();

        if ($this->eagerLoad) {
            $this->model = $this->model->with($this->eagerLoad);
        }

        if ($id) {
            return $this->model->where('id', $id)->firstOrFail();
        }

        return $this->model->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Ressource Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Get all items of this resource.
     * @return Collection       Collection of items
     */
    public function all()
    {
        $this->runChecks('read');

        $this->order();

        return $this->get();
    }

    /**
     * Get a single resource item by its ID.
     * @param  int   $id   Item ID
     * @return BaseModel       Model of the item
     */
    public function single($id)
    {
        $this->runChecks('read');

        return $this->get($id);
    }

    /**
     * Store a new resource item.
     * @param  array $input Raw user input
     * @return bool
     */
    public function store($input)
    {
        $this->model = $this->model->fill($input);

        $filteredInput = $this->runChecks('store', $this->model->toArray());

        if ($filteredInput) {
            $this->model->fill($filteredInput);
        }

        return $this->model->save();
    }

    /**
     * Update an existing resource item by ID.
     * @param  int $id    Item's ID
     * @param  array $input   Raw user input
     * @return bool
     */
    public function update($id, $input)
    {
        $this->model = $this->model->query()->findOrFail($id);

        $this->model = $this->model->fill($input);

        $filteredInput = $this->runChecks('update', $this->model->toArray(), $this->model->getOriginal());

        if ($filteredInput) {
            $this->model->fill($filteredInput);
        }

        return $this->model->save();
    }

    /**
     * Destroy an existing resource item by ID.
     * @param  int $id    Item's ID
     * @return bool
     */
    public function destroy($id)
    {
        $this->model = $this->model->findOrFail($id);

        $this->runChecks('destroy', $this->model->toArray());

        return $this->model->delete();
    }

    /**
     * Get id of resource.
     * @return mixed
     */
    public function id()
    {
        // By default just return this resource's id
        if (isset($this->model->id)) {
            return $this->model->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Default Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Filters to enforce during resource read.
     */
    protected function filter()
    {
    }

    /**
     * Check if read operations are authorised on the service
     * Typically redifined in subclasses.
     * @return bool
     */
    protected function readAuthorised()
    {
        return false;
    }

    /**
     * Check if store operations are authorised on the service
     * Typically redifined in subclasses.
     * @return bool
     */
    protected function storeAuthorised()
    {
        return false;
    }

    /**
     * Check if update operations are authorised on the service
     * Typically redifined in subclasses.
     * @return bool
     */
    protected function updateAuthorised()
    {
        return false;
    }

    /**
     * Check if destroy operations are authorised on the service
     * Typically redifined in subclasses.
     * @return bool
     */
    protected function destroyAuthorised()
    {
        return false;
    }

    /**
     * Run rule checks that apply during resource read
     * Typically redifined in subclasses.
     * @param  array $input     Input data to be used when evaluating rules
     * @throws DomainException	when a rule is broken
     */
    protected function domainRulesOnRead($input)
    {
    }

    /**
     * Run rule checks that apply during resource store
     * Typically redifined in subclasses.
     * @param  array $input     Input data to be used when evaluating rules
     * @throws DomainException	when a rule is broken
     */
    protected function domainRulesOnStore($input)
    {
    }

    /**
     * Run rule checks that apply during resource update
     * Typically redifined in subclasses.
     * @param  array $input     Input data to be used when evaluating rules
     * @param  array $original  Original item data to be used when evaluating rules
     * @throws DomainException	when a rule is broken
     */
    protected function domainRulesOnUpdate($input, $original)
    {
    }

    /**
     * Run rule checks that apply during resource destroy
     * Typically redifined in subclasses.
     * @param  array $original  original data to be used when evaluating rules
     * @throws DomainException	when a rule is broken
     */
    protected function domainRulesOnDestroy($original)
    {
    }
}
