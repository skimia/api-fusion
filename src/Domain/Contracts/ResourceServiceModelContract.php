<?php
/**
 * Created by PhpStorm.
 * User: Jean-françois
 * Date: 21/08/2015
 * Time: 07:58
 */

namespace Skimia\ApiFusion\Domain\Contracts;


interface ResourceServiceModelContract
{

    /**
     * Begin querying a model with eager loading.
     *
     * @param  array|string  $relations
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public static function with($relations);


    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes);

    /**
     * Begin querying the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function query();

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray();

}