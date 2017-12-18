<?php

namespace BitSensor\Handler;


use BitSensor\Core\ApiConnector;
use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Exception\ApiException;
use Proto\Datapoint;

/**
 * Handler to run after the PHP script finished. Sends logged data to the BitSensor servers.
 * @package BitSensor\Handler
 */
class AfterRequestHandler
{

    /**
     * @param Datapoint $datapoint Datapoint containing the data about the connecting user.
     * @param Config $config The Config.
     */
    public static function handle($datapoint, $config)
    {
        global $bitsensorNoShutdownHandler;
        if ($bitsensorNoShutdownHandler !== null && $bitsensorNoShutdownHandler) {
            return;
        }

        // Correctly sets working directory
        chdir(BITSENSOR_WORKING_DIR);

        if ($config->getOutputFlushing() === Config::OUTPUT_FLUSHING_ON) {
            ob_flush();
            flush();
        }

        if ($config->getFastcgiFinishRequest() === Config::EXECUTE_FASTCGI_FINISH_REQUEST_ON) {
            fastcgi_finish_request();
        }

        try {
            ApiConnector::from($config->getUser(), $config->getApiKey())
                ->with($datapoint)
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
