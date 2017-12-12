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
                if(isset($_SERVER['REMOTE_ADDR'])) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }

                break;
            case Config::IP_ADDRESS_X_FORWARDED_FOR:
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR'], 2)[0];
                }

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
