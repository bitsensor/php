<?php

namespace BitSensor\Handler;


use BitSensor\Core\ApiConnector;
use BitSensor\Core\BitSensor;
use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Exception\ApiException;
use Proto\Datapoint;
use Proto\Error;

/**
 * Handler to run after the PHP script finished. Sends logged data to the BitSensor servers.
 * @package BitSensor\Handler
 */
class AfterRequestHandler
{

    /**
     * @param BitSensor $bitSensor.
     */
    public static function handle($bitSensor)
    {
        // Correctly sets working directory
        chdir(BITSENSOR_WORKING_DIR);

        if ($bitSensor->getConfig()->getOutputFlushing() === Config::OUTPUT_FLUSHING_ON) {
            ob_flush();
            flush();
        }

        if ($bitSensor->getConfig()->getFastcgiFinishRequest() === Config::EXECUTE_FASTCGI_FINISH_REQUEST_ON) {
            fastcgi_finish_request();
        }

        foreach (PsrLogHandler::getBuffer() as $error) {
            $bitSensor->addError($error);
        }

        if(PsrLogHandler::isOverflown()) {
            $error_msg = "BitSensor PSR Logger Buffer is overflown. 
            To prevent this, initialize BitSensor earlier or increase the bufferSize of PsrLogHandler. 
            The current size is " . PsrLogHandler::getBufferSize();

            $error = new Error();
            $error->setDescription($error_msg);
            $error->setType("config");
            $bitSensor->addError($error);

            trigger_error($error_msg, E_USER_WARNING);
        }

        try {
            ApiConnector::from($bitSensor->getConfig()->getUser(), $bitSensor->getConfig()->getApiKey())
                ->with($bitSensor->getDatapoint())
                ->to($bitSensor->getConfig()->getUri())
                ->post(ApiConnector::ACTION_LOG)
                ->send();
        } catch (ApiException $e) {
            if ($bitSensor->getConfig()->getLogLevel() === Config::LOG_LEVEL_ALL) {
                error_log($e->getMessage());
            }
        }
    }
}
