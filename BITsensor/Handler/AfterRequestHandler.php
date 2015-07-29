<?php

namespace BITsensor\Handler;


use BITsensor\Core\ApiConnector;
use BITsensor\Core\Collector;

class AfterRequestHandler {

    public static function handle() {
        /**
         * @var Collector
         */
        global $bitSensor;

        // Correctly sets working directory
        chdir(WORKING_DIR);

        ApiConnector::with($bitSensor->toArray())->send();
    }

}