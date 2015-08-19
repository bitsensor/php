<?php

namespace BitSensor\Handler;

use BitSensor\Core\Collector;
use BitSensor\Core\Config;
use BitSensor\Core\IpContext;

/**
 * Collects the IP address.
 * @package BitSensor\Handler
 */
class IpHandler implements Handler {

    /**
     * @param Collector $collector
     * @param Config $config
     */
    public function handle(Collector $collector, Config $config) {
        $ip = null;

        switch ($config->getIpAddressSrc()) {
            case Config::IP_ADDRESS_REMOTE_ADDR:
                $ip = $_SERVER['REMOTE_ADDR'];
                break;
            case Config::IP_ADDRESS_X_FORWARDED_FOR:
                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
                break;
            case Config::IP_ADDRESS_MANUAL:
                $ip = $config->getIpAddress();
                break;
        }

        $collector->addContext(new IpContext($ip));
    }
}