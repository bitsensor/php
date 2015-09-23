<?php

namespace BitSensor\Handler;


use BitSensor\Core\ApiConnector;
use BitSensor\Core\Collector;
use BitSensor\Exception\ApiException;

/**
 * Handler to run after the PHP script finished. Sends logged data to the BitSensor servers.
 * @package BitSensor\Handler
 */
class AfterRequestHandler {

    /**
     * @param string $user Your BitSensor username.
     * @param string $apiKey Your BitSensor API key.
     * @param Collector $collector The Collector containing the data about the connecting user.
     * @param string $uri The BitSensor server to connect to.
     * @throws ApiException
     */
    public static function handle($user, $apiKey, $collector, $uri) {

        // Correctly sets working directory
        chdir(WORKING_DIR);

        try {
            ApiConnector::from($user, $apiKey)
                ->with($collector->toArray())
                ->to($uri)
                ->post(ApiConnector::ACTION_LOG)
                ->send();
        } catch (ApiException $e) {
            error_log($e->getMessage());
        }
    }
}