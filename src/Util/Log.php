<?php

namespace BitSensor\Util;


/**
 * Helper class for logging stuff.
 * @package BitSensor\Util
 */
class Log {

    /**
     * Prints a messages if <code>$debug</code> is in the global scope.
     *
     * Example:
     * <code>
     * global $debug;
     * </code>
     *
     * @param string $msg The message to print.
     */
    public static function d($msg) {
        if (array_key_exists('debug', $GLOBALS)) {
            echo $msg;
        }
    }

}