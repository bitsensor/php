<?php

namespace BitSensor\Handler;


use BitSensor\Core\Collector;
use BitSensor\Core\InputContext;

/**
 * Collects information about the HTTP request data.
 * @package BitSensor\Handler
 */
class RequestInputHandler {

    /**
     * @param Collector $collector
     */
    public static function handle(Collector $collector) {
        $collector->addInput(new InputContext(InputContext::POST, $_POST));
        $collector->addInput(new InputContext(InputContext::GET, $_GET));
        $collector->addInput(new InputContext(InputContext::COOKIE, $_COOKIE));
    }

}