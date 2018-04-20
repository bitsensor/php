<?php

namespace BitSensor\Handler;

use Proto\Datapoint;

/**
 * Collects the IP address.
 * @package BitSensor\Handler
 */
class IpHandler extends AbstractHandler
{
    /**
     * Source of the IP address of the user, by default to {@see IP_ADDRESS_REMOTE_ADDR}.
     *
     * One of:
     *  - {@see IP_ADDRESS_REMOTE_ADDR}
     *  - {@see IP_ADDRESS_X_FORWARDED_FOR}
     *  - {@see IP_ADDRESS_MANUAL}
     */
    private static $ipAddressSrc = self::IP_ADDRESS_REMOTE_ADDR;

    private static $ip;

    /**
     * Set IP address manually.
     */
    const IP_ADDRESS_MANUAL = 'manual';
    /**
     * Set IP address according to <code>$_SERVER['REMOTE_ADDR']</code>.
     */
    const IP_ADDRESS_REMOTE_ADDR = 'remoteAddr';
    /**
     * Set IP address according to the <code>X-Forwarded-For</code> HTTP header.
     */
    const IP_ADDRESS_X_FORWARDED_FOR = 'forwardedFor';

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
     * @param string[] $config
     * @return mixed|void
     */
    public function configure($config)
    {
        parent::configure($config);

        if (array_key_exists('ipAddressSrc', $config))
            self::$ipAddressSrc = $config['ipAddressSrc'];

        if (array_key_exists('ipAddress', $config))
            self::$ip = $config['ipAddress'];
    }

    /**
     * @param Datapoint $datapoint
     */
    public function doHandle(Datapoint $datapoint)
    {
        $ip = null;

        switch (self::$ipAddressSrc) {
            case self::IP_ADDRESS_REMOTE_ADDR:
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }

                break;
            case self::IP_ADDRESS_X_FORWARDED_FOR:
                if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip_array = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR'], 2);
                    $ip = $ip_array[0];
                }

                break;
            case self::IP_ADDRESS_MANUAL:
                $ip = self::$ip;
                break;
        }

        if ($ip !== null) {
            $datapoint->getContext()['ip'] = $ip;
        }
    }

}
