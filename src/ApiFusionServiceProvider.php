<?php namespace Skimia\ApiFusion;



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

class ApiFusionServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->app->register(DingoApiServiceProvider::class);
        $this->app->register(SentinelServiceProvider::class);
        $this->app->register(ValidationServiceProvider::class);

        $loader = AliasLoader::getInstance();

        $loader->alias('API', API::class);
        $loader->alias('APIRoute', Route::class);

        $loader->alias('Activation', Activation::class);
        $loader->alias('Reminder', Reminder::class);
        $loader->alias('Sentinel', Sentinel::class);
        
    }

}