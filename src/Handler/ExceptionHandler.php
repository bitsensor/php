<?php

namespace BitSensor\Handler;

use Exception;
use Proto\Datapoint;
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
        global /** @var Datapoint $datapoint */
        $datapoint;

        $error = new Error();
        $error->setCode($exception->getCode());
        $error->setDescription($exception->getMessage());
        $error->setLocation($exception->getFile());
        $error->setLine($exception->getLine());
        $error->setType('Exception');

        $traces = explode(PHP_EOL, $exception->getTraceAsString());
        $error->setContext($traces);

        $datapoint->getErrors()[] = $error;

        global $bitSensor;
        if (isset($bitSensor->exceptionHandler))
            call_user_func($bitSensor->exceptionHandler, $exception);
    }
}
