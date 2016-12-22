<?php

namespace BitSensor\Handler;


use BitSensor\Core\CodeError;
use BitSensor\Core\Collector;
use Exception;

/**
 * Collects information about thrown exceptions.
 * @package BitSensor\Handler
 */
class ExceptionHandler {

    /**
     * @param Exception $exception
     */
    public static function handle($exception) {
        /**
         * @global Collector $collector
         */
        global $collector;
        $collector->addError(new CodeError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace(), 'Exception'));

	global $bitSensor;
	if(isset($bitSensor->exceptionHandler))
            call_user_func($bitSensor->exceptionHandler, $exception);
    }
}
