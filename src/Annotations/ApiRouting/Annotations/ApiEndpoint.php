<?php

namespace Skimia\ApiFusion\Annotations\ApiRouting\Annotations;

/**
         * @Annotation
         */
        class ApiEndpoint
        {
            /**
             * The events the annotation hears.
             *
             * @var array
             */
            public $resourceEndPoint;

            /**
             * The api version.
             *
             * @var array
             */
            public $version;

            /**
             * The events the annotation hears.
             *
             * @var array
             */
            public $verb;

            /**
             * The events the annotation hears.
             *
             * @var array
             */
            public $values;

            /**
             * Create a new annotation instance.
             *
             * @param  array $values
             *
             * @return void
             */
            public function __construct(array $values = [])
            {
                $this->resourceEndPoint = $values['value'];
                $this->version = $values['version'];
                $this->verb = isset($values['verb']) ? $values['verb'] : 'get';

                unset($values['value'], $values['version'],$values['verb']);

                $this->values = (array) $values;
            }
        }
