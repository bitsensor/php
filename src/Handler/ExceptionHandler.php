<?php

namespace BitSensor\Handler;

use BitSensor\Core\BitSensor;
use Exception;
use Proto\Error;

/**
 * Collects information about thrown exceptions.
 * @package BitSensor\Handler
 */
class ExceptionHandler
{

    /**
     * @param Exception $exception
     */
    public static function handle($exception)
    {
        $error = new Error();
        $error->setCode($exception->getCode());
        $error->setDescription($exception->getMessage());
        $error->setLocation($exception->getFile());
        $error->setLine($exception->getLine());
        $error->setType('Exception');

        $traces = explode(PHP_EOL, $exception->getTraceAsString());
        $error->setContext($traces);

        BitSensor::addError($error);
    
        if (isset(BitSensor::$exceptionHandler))
            call_user_func(BitSensor::$exceptionHandler, $exception);
    }
}
