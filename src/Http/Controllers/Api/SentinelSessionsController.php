<?php

namespace Skimia\ApiFusion\Http\Controllers\Api;

use Input;
use Sentinel;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SentinelSessionsController extends ApiController
{
    public function __construct()
    {
        $this->middleware('api.auth', ['only' => ['index', 'user']]);
    }

    public function index()
    {
        return Sentinel::getUser()->persistences;
    }

    public function user()
    {
        if ($user = Sentinel::getUser()) {
            return $user;
        }
    }

    public function store()
    {
        try {
            if ($user = Sentinel::authenticate(
                Input::only('email', 'password'),
                Input::get('remember-me', 0))
            ) {
                return $user;
            } else {
                throw new UnauthorizedHttpException('Invalid Credentials');
            }
        } catch (\Exception $e) {
            //dd(get_class($e));
            throw new UnauthorizedHttpException('Unauthorized', $e->getMessage());
        }
    }
    
    public function storeToken()
    {
        try {

            if ( $user = Sentinel::stateless(Input::only('email', 'password'))) {
                return \JWTAuth::fromUser($user);
            }else{
                throw new UnauthorizedHttpException('Invalid Credentials');
            }
            
        } catch (\Exception $e) {
            //dd(get_class($e));
            throw new UnauthorizedHttpException('Unauthorized', $e->getMessage());
        }
    }
}
