<?php

namespace BitSensor\Handler;


use BitSensor\Core\ApiConnector;
use BitSensor\Core\Collector;

class AfterRequestHandler {

    /**
     * @param string $apiKey
     * @param Collector $collector
     * @param string $uri
     */
    public static function handle($apiKey, $collector, $uri) {

        // Correctly sets working directory
        chdir(WORKING_DIR);

        ApiConnector::from($apiKey)->with($collector->toArray())->to($uri)->send();
    }
}