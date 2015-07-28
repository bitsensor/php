<?php

namespace BITsensor\Handler;


use BITsensor\Core\Collector;

class AfterRequestHandler {

    public static function handle() {
        /**
         * @var Collector
         */
        global $bitSensor;

        echo $bitSensor;
    }

}