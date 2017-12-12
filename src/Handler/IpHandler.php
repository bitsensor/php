<?php

namespace BitSensor\Handler;

use BitSensor\Core\Config;
use Proto\Datapoint;

/**
 * Collects the IP address.
 * @package BitSensor\Handler
 */
class IpHandler implements Handler
{

    /**
     * @param Datapoint $datapoint
     * @param Config $config
     */
    public function handle(Datapoint $datapoint, Config $config)
    {
        $ip = null;

        switch ($config->getIpAddressSrc()) {
            case Config::IP_ADDRESS_REMOTE_ADDR:
                if(isset($_SERVER['REMOTE_ADDR'])) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }

                break;
            case Config::IP_ADDRESS_X_FORWARDED_FOR:
                if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip_array = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR'], 2);
                    $ip = $ip_array[0];
                }

                break;
            case Config::IP_ADDRESS_MANUAL:
                $ip = $config->getIpAddress();
                break;
        }

        if ($ip !== null) {
            $datapoint->getContext()['ip'] = $ip;
        }
    }
}
