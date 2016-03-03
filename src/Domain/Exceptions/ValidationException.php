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


        $this->validationErrors = $validationErrors;
        $message .= ' : '.implode(', ',$this->getValidationErrors());
        parent::__construct($message);
    }



    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
