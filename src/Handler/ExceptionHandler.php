<?php

namespace BITsensor\Handler;


use BITsensor\Core\CodeError;
use BITsensor\Core\Collector;
use Exception;

/**
 * Collects information about thrown exceptions.
 * @package BITsensor\Handler
 */
class ExceptionHandler {

    /**
     * @param Exception $exception
     */
    public static function handle($exception) {
        /**
         * @var Collector
         */
        global $bitSensor;

        $bitSensor->addError(new CodeError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace()));
    }

}