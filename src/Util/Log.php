<?php

namespace BitSensor\Util;

use BitSensor\Core\BitSensor;

/**
 * Helper class for logging stuff.
 * @package BitSensor\Util
 */
class Log
{
    private static $enabled = false; 

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
    public static function d($msg)
    {
        if (self::isEnabled()) {
            echo $msg;
        }
    }

    /**
     * Set logging state
     *
     * @param bool $enabled
     */
    public static function setEnabled($enabled){
        self::$enabled = $enabled;
    }

    /**
     * Returns wether logging is enabled, to be called before expensive logging calls.
     *
     * @return boolean
     */
    public static function isEnabled(){
        global $debug;
        return $debug === true || self::$enabled || BitSensor::$logLevel >= E_USER_NOTICE;
    }

}