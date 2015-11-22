<?php
namespace Skimia\ApiFusion\Domain\Exceptions;

use Exception;

class ValidationException extends Exception
{

    private $validationErrors;

    public function __construct(
        $message,
        $validationErrors = []
    ) {
        parent::__construct($message);

        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors() { return $this->validationErrors; }

}