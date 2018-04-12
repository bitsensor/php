<?php

namespace BitSensor\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Exception\ApiException;

/**
 * Handler to run after the PHP script finished. Sends logged data to the BitSensor servers.
 * @package BitSensor\Handler
 */
class AfterRequestHandler
{

    /**
     */
    public static function handle()
    {
        // Correctly sets working directory
        chdir(BITSENSOR_WORKING_DIR);

        if (BitSensor::getConfig()->getOutputFlushing() === Config::OUTPUT_FLUSHING_ON) {
            ob_flush();
            flush();
        }

        if (BitSensor::getConfig()->getFastcgiFinishRequest() === Config::EXECUTE_FASTCGI_FINISH_REQUEST_ON) {
            fastcgi_finish_request();
        }

        try {
            BitSensor::finish();
        } catch (ApiException $e) {
            if (BitSensor::getConfig()->getLogLevel() === Config::LOG_LEVEL_ALL) {
                error_log($e->getMessage());
            }
        }
    }
}
