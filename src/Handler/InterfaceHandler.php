<?php

namespace BitSensor\Handler;

use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Core\EndpointContext;
use BitSensor\Util\ServerInfo;


/**
 * Collects information about the used interface.
 * @package BitSensor\Handler
 */
class InterfaceHandler implements Handler {

    /**
     * @param Collector $collector
     * @param Config $config
     */
    public function handle(Collector $collector, Config $config) {
        $collector->addEndpointContext(new EndpointContext(EndpointContext::CLI, ServerInfo::isCli()));
        $collector->addEndpointContext(new EndpointContext(EndpointContext::SAPI, php_sapi_name()));
    }
}