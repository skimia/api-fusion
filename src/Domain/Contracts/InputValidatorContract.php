<?php
/**
 * Created by PhpStorm.
 * User: Jean-françois
 * Date: 21/08/2015
 * Time: 07:58
 */

namespace Skimia\ApiFusion\Domain\Contracts;


interface InputValidatorContract
{

    /**
     * Static factory.
     *
     * @param  array  $inputs
     * @return InputValidatorContract
     */
    public static function make($inputs = null);

    /**
     * Set the scope.
     *
     * @param string|array $scope
     * @return InputValidatorContract
     */
    public function scope($scope);

    /**
     * Binds a rule parameter.
     *
     * @return InputValidatorContract
     */
    public function bind();

    /**
     * Get the inputs. Especially useful for getting
     * the filtered input values.
     *
     * @return array
     */
    public function getInputs();

    /**
     * Test if the input data passes validation
     * @return bool
     */
    public function passes();

    /**
     * Test if the input data fails validation
     * @return bool
     */
    public function fails();

}