<?php

namespace BitSensor\Handler;

use BitSensor\Core\Config;
use Proto\Datapoint;

/**
 * Collects the IP address.
 * @package BitSensor\Handler
 */
class IpHandler extends AbstractHandler
{
    private static $ipAddressSrc = Config::IP_ADDRESS_REMOTE_ADDR;
    private static $ip;

    public function configure(Config $config)
    {
        parent::configure($config);
        self::setIpAddressSrc($config->getIpAddressSrc());
        self::setIp($config->getIpAddress());
    }

    /**
     * @param mixed $ipAddressSrc
     */
    public static function setIpAddressSrc($ipAddressSrc)
    {
        self::$ipAddressSrc = $ipAddressSrc;
    }

    /**
     * @param mixed $ip
     */
    public static function setIp($ip)
    {
        self::$ip = $ip;
    }

    /**
     * @param Datapoint $datapoint
     */
    public function doHandle(Datapoint $datapoint)
    {
        $ip = null;

        switch (self::$ipAddressSrc) {
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
                $ip = self::$ip;
                break;
        }

        if ($ip !== null) {
            $datapoint->getContext()['ip'] = $ip;
        }
    }

}
