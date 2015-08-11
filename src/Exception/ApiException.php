<?php

namespace BitSensor\Exception;



class ApiException{

    const CONNECTION_FAILED = 1;
    
    public function __construct($message) {
        echo $message . "<br />";
        trigger_error($message, E_WARNING);
    }
}