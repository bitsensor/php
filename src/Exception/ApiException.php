<?php

namespace BitSensor\Exception;



/**
 * Exception thrown when something went wrong while using the BitSensor server API.
 * @package BitSensor\Exception
 */
class ApiException{

    /**
     * Could not connect to specified server.
     */
    const CONNECTION_FAILED = 1;
    
    public function __construct($message) {
        echo $message . "<br />";
        trigger_error($message, E_WARNING);
    }
}