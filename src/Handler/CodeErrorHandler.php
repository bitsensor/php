<?php

namespace BitSensor\Handler;

use Proto\Datapoint;
use Proto\Error;

/**
 * Handler to run when an error in the application occurs. Collects data about.
 *
 * @package BitSensor\Handler
 */
class CodeErrorHandler
{

    /**
     * @param int $errno Error code.
     * @param string $errstr Error description.
     * @param string $errfile Name of the file in which the error occurred.
     * @param int $errline Line at which the error occurred.
     */
    public static function handle($errno, $errstr, $errfile, $errline, $errcontext)
    {

        global /** @var Datapoint $datapoint */
        $datapoint;

        $error = new Error();
        $error->setCode($errno);
        $error->setDescription($errstr);
        $error->setLocation($errfile);
        $error->setLine($errline);
        $error->setType("Code");

        $datapoint->getErrors()[] = $error;

        global $bitSensor;
        if (isset($bitSensor->errorHandler))
            call_user_func($bitSensor->errorHandler, $errno, $errstr, $errfile, $errline, $errcontext);
    }
}
