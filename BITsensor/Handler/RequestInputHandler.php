<?php

namespace BITsensor\Handler;


use BITsensor\Core\Collector;
use BITsensor\Core\InputContext;

/**
 * Collects information about the HTTP request data.
 * @package BITsensor\Handler
 */
class RequestInputHandler {

    /**
     * @param Collector $collector
     */
    public static function handle(Collector $collector) {
        $collector->addContext(new InputContext(InputContext::POST, $_POST));
        $collector->addContext(new InputContext(InputContext::GET, $_GET));
        $collector->addContext(new InputContext(InputContext::COOKIE, $_COOKIE));

    }

}