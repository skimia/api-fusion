<?php

namespace Skimia\ApiFusion\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    use Helpers;

    protected function requireAuth($actions = []){

        $this->beforeFilter('api.auth',['only'=>$actions]);

    }
}
