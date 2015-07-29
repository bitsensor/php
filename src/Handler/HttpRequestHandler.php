<?php

namespace BitSensor\Handler;

use BitSensor\Core\AuthenticatedContext;
use BitSensor\Core\AuthenticationContext;
use BitSensor\Core\Collector;
use BitSensor\Core\HttpContext;
use BitSensor\Core\IpContext;
use BitSensor\Core\ScriptContext;
use BitSensor\Core\ServerContext;

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
        $collector->addContext(new ScriptContext());
        $collector->addContext(new HttpContext());

        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $collector->addContext(new AuthenticationContext());
        }

        if (isset($_SERVER['REMOTE_USER'])) {
            $collector->addContext(new AuthenticatedContext());
        }

        $collector->addContext(new ServerContext());
    }

}