<?php

namespace Skimia\ApiFusion;

use Illuminate\Support\ServiceProvider;
use Dingo\Api\Provider\LaravelServiceProvider as DingoApiServiceProvider;
use KennedyTedesco\Validation\ValidationServiceProvider;
use Cartalyst\Sentinel\Laravel\SentinelServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Dingo\Api\Facade\API;
use Dingo\Api\Facade\Route;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Reminder;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Skimia\ApiFusion\Annotations\ApiRouting\ApiRoutingAnnotationsServiceProvider;
use Skimia\ApiFusion\Routing\RouteFusion;
use Skimia\ApiFusion\Facade\RouteFusion as RouteFusionFacade;
use Dingo\Api\Auth\Provider\Basic;
use Skimia\ApiFusion\Auth\Sentinel as AuthSentinel;

class ApiFusionServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->register(DingoApiServiceProvider::class);
        $this->app->register(SentinelServiceProvider::class);
        $this->app->register(ValidationServiceProvider::class);
        $this->app->register(ApiRoutingAnnotationsServiceProvider::class);

        $loader = AliasLoader::getInstance();

        $loader->alias('API', API::class);
        $loader->alias('APIRoute', Route::class);

        $loader->alias('Activation', Activation::class);
        $loader->alias('Reminder', Reminder::class);
        $loader->alias('Sentinel', Sentinel::class);

        $this->app->singleton('api-fusion.route', function ($app) {
            return new RouteFusion($app);
        });

        $loader->alias('RouteFusion', RouteFusionFacade::class);

        if (class_exists('Tymon\JWTAuth\Providers\LaravelServiceProvider')) {
            $this->app->register('Tymon\JWTAuth\Providers\LaravelServiceProvider');

            $loader->alias('JWTAuth', 'Tymon\JWTAuth\Facades\JWTAuth');
        }

        $this->commands([
            'Skimia\ApiFusion\Console\Commands\GenerateDomainApi',
        ]);
    }

    public function boot()
    {
        $this->app['api.auth']->extend('basic', function ($app) {
            return new Basic($app['auth']);
        });

        $this->app['api.auth']->extend('sentinel', function ($app) {
            return new AuthSentinel($app['sentinel']);
        });

        if (class_exists('Tymon\JWTAuth\Providers\LaravelServiceProvider')) {
            $this->app['api.auth']->extend('jwt', function ($app) {
                return new \Dingo\Api\Auth\Provider\JWT($app['Tymon\JWTAuth\JWTAuth']);
            });
        }
    }
}
