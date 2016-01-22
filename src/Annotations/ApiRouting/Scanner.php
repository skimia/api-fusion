<?php

namespace Skimia\ApiFusion\Annotations\ApiRouting;

use Skimia\Foundation\Annotations\Scanner as BaseScanner;
use Skimia\ApiFusion\Annotations\ApiRouting\Annotations\ApiResource;

class Scanner extends BaseScanner
{
    /**
     * {@inheritdoc}
     */
    public function getScannedAnnotationPath()
    {
        return $this->app['path.storage'].'/framework/api.routes.scanned.php';
    }

    public function getAnnotationsDefinitions()
    {
        $output = '';

        $reader = $this->getReader();

        /*
         * @var ReflectionClass
         */
        foreach ($this->getClassesToScan() as $class) {
            foreach ($reader->getClassAnnotations($class) as $annotation) {
                /*
                 * @var ApiResource
                 */
                if (get_class($annotation) == ApiResource::class) {
                    $output .= $this->buildApiRoute($class->getName(), $annotation->version, $annotation->resourceEndPoint, $annotation->values);
                }
            }

            /*foreach ($class->getMethods() as $method)
            {
                foreach ($reader->getMethodAnnotations($method) as $annotation)
                {
                    dd($annotation);
                    $output .= $this->buildListener($class->name, $method->name, $annotation->events);
                }
            }*/
        }

        return trim($output);
    }

    /**
     * Build the event listener for the class and method.
     *
     * @param  string  $class
     * @param  string  $method
     * @param  array  $events
     * @return string
     */
    protected function buildApiRoute($class, $version, $endpoint, $vars)
    {
        return sprintf('	$api->version("%s", function ($api) {'.PHP_EOL.
            '		$api->resource("%s", "%s",'.PHP_EOL.'%s'.PHP_EOL.'		);'.PHP_EOL.
            '	});'.PHP_EOL,
            $version,
            $endpoint,
            addslashes($class),
            var_export($vars, true));
    }

    public function loadScannedAnnotations()
    {
        if ($this->annotationsAreScanned()) {
            $api = $this->app['api.router'];

            require $this->getScannedAnnotationPath();

            return true;
        }

        return false;
    }
}
