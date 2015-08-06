<?php

namespace BitSensor\Handler;


use BitSensor\Core\ApiConnector;
use BitSensor\Core\Collector;
use BitSensor\Exception\ApiException;

class AfterRequestHandler {

    /**
     * @param string $user
     * @param string $apiKey
     * @param Collector $collector
     * @param string $uri
     * @throws ApiException
     */
    public static function handle($user, $apiKey, $collector, $uri) {

        // Correctly sets working directory
        chdir(WORKING_DIR);

        ApiConnector::from($user, $apiKey)
            ->with($collector->toArray())
            ->to($uri)
            ->post(ApiConnector::ACTION_LOG)
            ->send();
    }
}