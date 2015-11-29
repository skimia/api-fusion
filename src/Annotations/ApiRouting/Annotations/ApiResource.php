<?php

namespace Skimia\ApiFusion\Annotations\ApiRouting\Annotations;

/**
         * @Annotation
         */
        class ApiResource
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

                unset($values['value'], $values['version']);

                $this->values = (array) $values;
            }
        }
