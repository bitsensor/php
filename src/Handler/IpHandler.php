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
                $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
                break;
            case Config::IP_ADDRESS_X_FORWARDED_FOR:
                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
                break;
            case Config::IP_ADDRESS_MANUAL:
                $ip = $config->getIpAddress();
                break;
        }

        if ($ip !== null) {
            $collector->addContext(new IpContext($ip));
        }
    }
}