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
         * @global Collector $bitSensor
         */
        global $bitSensor;

        $bitSensor->addError(new CodeError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine()));
    }

}