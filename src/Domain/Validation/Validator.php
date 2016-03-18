<?php

namespace Skimia\ApiFusion\Domain\Validation;


use Fadion\ValidatorAssistant\ValidatorAssistant;

class Validator extends ValidatorAssistant
{

    /**
     * Binds a rule parameter.
     *
     * @return ValidatorAssistant
     */
    public function bind()
    {
        if (func_num_args()) {
            $bindings = new Bindings(func_get_args(), $this->rules);
            $this->rules = $bindings->rules();
        }

        return $this;
    }

    /**
     * Catch dynamic binding calls.
     */
    public function __call($name, $args)
    {
        if (strpos($name, 'bind') !== false and count($args) == 1) {
            $name = strtolower(substr($name, strlen('bind')));

            $bindings = new Bindings(array(array($name => $args[0])), $this->rules);
            $this->rules = $bindings->rules();

            return $this;
        }
    }
}