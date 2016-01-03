<?php

namespace Skimia\ApiFusion\Auth;

use Cartalyst\Sentinel\Users\EloquentUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends EloquentUser implements JWTSubject
{

    public function getJWTIdentifier()
    {
        return $this->id;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}