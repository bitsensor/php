<?php

namespace BitSensor\Handler;

use BitSensor\Core\AuthenticationContext;
use BitSensor\Core\Collector;
use BitSensor\Core\EndpointContext;
use BitSensor\Core\HttpContext;
use BitSensor\Core\IpContext;

/**
 * Collects information about the HTTP request.
 * @package BitSensor\Handler
 */
class HttpRequestHandler {

    /**
     * @param Collector $collector
     */
    public static function handle(Collector $collector) {
        $collector->addContext(new IpContext());
        $collector->addContext(new HttpContext());

        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $collector->addContext(new AuthenticationContext());
        }

        $collector->addContext(new EndpointContext());
    }

}