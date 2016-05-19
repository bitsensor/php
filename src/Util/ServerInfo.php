<?php

namespace BitSensor\Util;


/**
 * Helper class for collecting server information.
 * @package BitSensor\Util
 */
class ServerInfo {

    /**
     * Checks if PHP is executed using the CLI by looking at the SAPI name and if <code>STDIN</code> is connected.
     *
     * @return bool True if PHP is executed using the CLI.
     */
    public static function isCli() {
        return php_sapi_name() === 'cli' || defined('STDIN');
    }

    /**
     * Checks if the HTTP request is done over HTTPS.
     *
     * @return bool True if HTTPS is used.
     */
    public static function isHttps() {
        return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    }

}