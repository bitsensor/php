<?php

namespace BitSensor\Handler;


use BitSensor\Core\CodeError;
use BitSensor\Core\Collector;

/**
 * Handler to run when an error in the application occurs. Collects data about
 * @package BitSensor\Handler
 */
class CodeErrorHandler {

    /**
     * @param int $errno Error code.
     * @param string $errstr Error description.
     * @param string $errfile Name of the file in which the error occurred.
     * @param int $errline Line at which the error occurred.
     */
    public static function handle($errno, $errstr, $errfile, $errline) {
        /**
         * @global Collector $collector
         */
        global $collector;

        $collector->addError(new CodeError($errno, $errstr, $errfile, $errline, null, "Code"));
        
        if(mysql_errno())
        {
            $collector->addError(new CodeError(mysql_errno(), mysql_error(), $errfile, $errline, null, "MySQL"));
        }
    }
}