<?php
/**
 * Created by PhpStorm.
 * User: Jean-françois
 * Date: 21/08/2015
 * Time: 08:01
 */

namespace Skimia\ApiFusion\Domain\Users;

use Skimia\ApiFusion\Domain\Contracts\ServiceUserContract;
use Cartalyst\Sentinel\Users\EloquentUser;
use Eloquent;


class SentinelServiceUserAdapter implements ServiceUserContract
{

    protected $user = null;

    protected $id = null;

    public function __construct( EloquentUser $user = null )
    {
        if ( $user )
        {
            $this->user = $user;
            $this->id = $this->user->id;
        }
    }

    public function id()
    {
        return $this->id;
    }

    public function isAuthenticated()
    {
        return ! empty( $this->user );
    }

    public function hasRole( $role )
    {
        if ( $this->user )
            return $this->user->hasRole( $role );
        return false;
    }

}