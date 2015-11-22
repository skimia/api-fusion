<?php namespace Skimia\ApiFusion\Annotations\ApiRouting;



use Skimia\Foundation\Annotations\BaseServiceProvider;
use Skimia\ApiFusion\Annotations\ApiRouting\Scanner;

class ApiRoutingAnnotationsServiceProvider extends BaseServiceProvider {
    /**
     * @inheritDoc
     */
    protected function registerAnnotationScanner()
    {
        $this->app->bindShared('skimia.apifusion.annotations.apirouting.scanner', function ($app)
        {
            $scanner = new Scanner($app, []);

            $scanner->addAnnotationNamespace(
                'Skimia\ApiFusion\Annotations\ApiRouting\Annotations',
                __DIR__ . '/Annotations'
            );

            return $scanner;
        });
    }

    /**
     * @inheritDoc
     */
    protected function getAnnotationScanner()
    {
        return $this->app->make('skimia.apifusion.annotations.apirouting.scanner');
    }


}