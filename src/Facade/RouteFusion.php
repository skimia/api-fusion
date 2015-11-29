<?php

namespace Skimia\ApiFusion\Facade;

use Illuminate\Support\Facades\Facade;

class RouteFusion extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api-fusion.route';
    }
}
