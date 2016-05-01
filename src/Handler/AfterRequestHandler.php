<?php

namespace BitSensor\Handler;


use BitSensor\Core\ApiConnector;
use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Exception\ApiException;

/**
 * Handler to run after the PHP script finished. Sends logged data to the BitSensor servers.
 * @package BitSensor\Handler
 */
class AfterRequestHandler {

    /**
     * @param Collector $collector The Collector containing the data about the connecting user.
     * @param Config $config The Config.
     * @throws ApiException
     */
    public static function handle($collector, $config) {

        // Correctly sets working directory
        chdir(WORKING_DIR);

        if ($config->getOutputFlushing() === Config::OUTPUT_FLUSHING_ON) {
            ob_flush();
            flush();
        }

        try {
            sleep(5);
            ApiConnector::from($config->getUser(), $config->getApiKey())
                ->with($collector->toArray())
                ->to($config->getUri())
                ->post(ApiConnector::ACTION_LOG)
                ->send();
        } catch (ApiException $e) {
            if ($config->getLogLevel() === Config::LOG_LEVEL_ALL) {
                error_log($e->getMessage());
            }
        }
    }
}