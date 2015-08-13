<?php

namespace BitSensor\Exception;

use Exception;


/**
 * Exception thrown when something went wrong while using the BitSensor server API.
 * @package BitSensor\Exception
 */
class ApiException extends Exception {

    /**
     * Could not connect to specified server.
     */
    const CONNECTION_FAILED = 1;

}