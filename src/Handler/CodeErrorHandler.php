<?php

namespace BitSensor\Handler;


use BitSensor\Core\CodeError;
use BitSensor\Core\Collector;

class CodeErrorHandler {

    /**
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @param array $errcontext
     */
    public static function handle($errno, $errstr, $errfile, $errline, $errcontext) {
        /**
         * @var Collector
         */
        global $bitSensor;

        $bitSensor->addError(new CodeError($errno, $errstr, $errfile, $errline, $errcontext));
    }

}