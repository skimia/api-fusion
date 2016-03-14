<?php
/**
 * Created by PhpStorm.
 * User: Jean-françois
 * Date: 22/08/2015
 * Time: 11:36.
 */
namespace Skimia\ApiFusion\Domain\Traits;

use Skimia\ApiFusion\Domain\Exceptions\ValidationException;
use Skimia\ApiFusion\Domain\Exceptions\AuthorisationException;
use Skimia\ApiFusion\Domain\Exceptions\RequiredInputValidatorException;

trait CheckableTrait
{
    /**
     * Validator class for the resource.
     * @var InputValidatorContract
     */
    protected $inputValidator;

    /*
    |--------------------------------------------------------------------------
    | Security Authorisation & Validation & Filters
    |--------------------------------------------------------------------------
    */

    /**
     * Run required checks for given action.
     * @param  string  $action   Requested action to check
     * @param  array   $input    Input data relevant to checks
     * @param  array   $original Original data from the model
     * @return array             InputFilteredData if $input is provided
     */
    protected function runChecks($action, $input = [], $original = [])
    {
        // Perform a basic check to see if the current action is authorised
        // to be performed on this resource
        $this->checkAuthorisation($action);

        // If input data is given
        if (! empty($input)) {
            // Run input validation on the input data
            $filtered = $this->applyValidationRules($action, $input);

            // Run business rule validation on the input data
            $this->applyDomainRules($action, $input, $original);

            return $filtered;
        }
    }

    /**
     * Check if given action is authorised on resource.
     * @param  string  $action   Requested action to check
     * @throws AuthorisationException   If action is unathorised
     */
    protected function checkAuthorisation($action)
    {
        $authCheckMethod = $action.'Authorised'; // e.g. readAuthorised

        if (! $this->{ $authCheckMethod }()) {
            throw new AuthorisationException('You are not authorised to perform this action');
        }
    }

    /**
     * Apply validation rules.
     * @param  string  $action                  Requested action to check
     * @param  array   $input                   Input data
     * @throws ValidationException              If input validation fails
     * @throws RequiredInputValidatorException  If inputValidator is not proivide on a data modification / creation
     * @return array                            InputFilteredData
     */
    protected function applyValidationRules($action, $input = [])
    {
        if ($action == 'read' or $action == 'destroy') {
            return;
        }

        if (! $this->inputValidator) {
            throw new RequiredInputValidatorException('You must provide an inputValidator to perform a data modification / creation');
        }

        $validation = $this->inputValidator->make($input);

        $validation->scope([$action]);

        $validation->bind($validation->getInputs()); // provide all input data for use in rule definitions

        if ($validation->fails()) {
            throw new ValidationException('Validation failed', $validation->errors()->all());
        } else {
            return $validation->getInputs();
        }
    }

    /**
     * Apply domain rules.
     * @param  string  $action      Requested action to check
     * @param  array   $input       Input data
     * @param  array   $original    Original item before modification
     */
    protected function applyDomainRules($action, $input = [], $original = [])
    {
        $domainRulesMethod = 'domainRulesOn'.ucfirst($action);

        // Pass in original model if we are updating
        if ($action = 'update') {
            $this->{ $domainRulesMethod }($input, $original);
        } else {
            $this->{ $domainRulesMethod }($input);
        }
    }
}
