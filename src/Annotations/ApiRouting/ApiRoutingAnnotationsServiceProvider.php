<?php

namespace Skimia\ApiFusion\Annotations\ApiRouting;

use Skimia\Foundation\Annotations\BaseServiceProvider;

class ApiRoutingAnnotationsServiceProvider extends BaseServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected function registerAnnotationScanner()
    {
        $this->app->bindShared('skimia.apifusion.annotations.apirouting.scanner', function ($app) {
            $scanner = new Scanner($app, []);

            $scanner->addAnnotationNamespace(
                'Skimia\ApiFusion\Annotations\ApiRouting\Annotations',
                __DIR__.'/Annotations'
            );

            return $scanner;
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function getAnnotationScanner()
    {
        return $this->app->make('skimia.apifusion.annotations.apirouting.scanner');
    }
}
