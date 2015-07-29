<?php

namespace BITsensor\Handler;

use BITsensor\Core\AuthenticatedContext;
use BITsensor\Core\AuthenticationContext;
use BITsensor\Core\Collector;
use BITsensor\Core\HttpContext;
use BITsensor\Core\IpContext;
use BITsensor\Core\ScriptContext;
use BITsensor\Core\ServerContext;

/**
 * Collects information about the HTTP request.
 * @package BITsensor\Handler
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