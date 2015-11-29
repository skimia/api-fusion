<?php

namespace  Skimia\ApiFusion\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Auth\Provider\Authorization;
use Cartalyst\Sentinel\Sentinel as AuthManager;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class Sentinel extends Authorization
{
    /**
     * Illuminate authentication manager.
     *
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Basic auth identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Create a new basic provider instance.
     *
     * @param \Illuminate\Auth\AuthManager $auth
     * @param string                       $identifier
     *
     * @return void
     */
    public function __construct(AuthManager $auth, $identifier = 'email')
    {
        $this->auth = $auth;
        $this->identifier = $identifier;
    }

    /**
     * Authenticate request with Basic.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \Illuminate\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route)
    {
        $this->validateAuthorizationHeader($request);

        if ($user = $this->auth->getUser()) {
            return $user;
        }

        throw new UnauthorizedHttpException(null, 'Please log in before perform this query.');
    }

    /**
     * Validate the requests authorization header for the provider.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     *
     * @return bool
     */
    public function validateAuthorizationHeader(Request $request)
    {
        if (Str::startsWith(strtolower($request->headers->get('shield')), $this->getAuthorizationMethod())) {
            return true;
        }

        throw new BadRequestHttpException;
    }

    /**
     * Get the providers authorization method.
     *
     * @return string
     */
    public function getAuthorizationMethod()
    {
        return 'sentinel';
    }
}
