<?php

namespace Skimia\ApiFusion\Routing;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Dingo\Api\Http\Parser\Accept;
use Response;
use APIRoute;
use Skimia\ApiFusion\Http\Controllers\Api\SentinelSessionsController;

class RouteFusion
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    public function apiIndexListing($api, $url = '/', $name = 'api-fusion.index')
    {
        $api->get($url, ['as' => $name, function (Request $request) {

            $accept = $this->getCurrentAcceptHeader($request);

            $routes = APIRoute::getRoutes($accept['version']);
            $endpoints = [];
            foreach ($routes as $route) {
                $endpoints['endpoints'][] =
                    [
                        'path' => $route->getPath(),
                        'methods' => $route->getMethods(),
                    ];
            }

            return Response::make($endpoints, 200, [], ['options' => JSON_PRETTY_PRINT]);
        }]);
    }

    public function apiLoginHTTP($api)
    {
        $sessions_class = SentinelSessionsController::class;
        $api->post('login', $sessions_class.'@store');
        $api->get('user', $sessions_class.'@user');

        $api->get('sessions', $sessions_class.'@index');
        $api->get('sessions/kill', $sessions_class.'@kill');
        $api->get('sessions/kill/all', $sessions_class.'@killAll');
        $api->get('sessions/kill/{code}', $sessions_class.'@killByCode');
    }

    public function apiLoginJWT($api)
    {
        $sessions_class = SentinelSessionsController::class;
        $api->post('login', $sessions_class.'@storeToken');
        $api->get('user', $sessions_class.'@user');

        $api->get('sessions', $sessions_class.'@index');
        $api->get('sessions/kill', $sessions_class.'@kill');
        $api->get('sessions/kill/all', $sessions_class.'@killAll');
        $api->get('sessions/kill/{code}', $sessions_class.'@killByCode');
    }

    public function getCurrentAcceptHeader($request = null)
    {
        if (! isset($request)) {
            $request = Request::instance();
        }

        $config = $this->app['config']['api'];

        $acceptClass = new Accept($config['standardsTree'], $config['subtype'], $config['version'], $config['defaultFormat']);

        return $acceptClass->parse($request);
    }
}
