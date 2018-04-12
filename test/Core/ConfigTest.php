<?php

namespace BitSensor\Test\Core;


use BitSensor\Connector\TestConnector;
use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use PHPUnit_Framework_Error_Warning;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $json = '
        {
            "mode": "on",
            "ipAddressSrc": "manual",
            "ipAddress": "127.0.0.1",
            "logLevel": "none",
            "outputFlushing": "off", 
            "executeFastCgi": "off",
            "uopzHook": "off",
            "connector": {
                "type": "test",
                "user": "dev",
                "apikey": "php-plugin-test"
            } 
        }';

    public function testEmptyConstructor()
    {
        $config = new Config();

        static::assertEquals(Config::MODE_DETECTION, $config->getMode());
        static::assertEquals(Config::IP_ADDRESS_REMOTE_ADDR, $config->getIpAddressSrc());
        static::assertEmpty($config->getIpAddress());
        static::assertEquals(Config::LOG_LEVEL_ALL, $config->getLogLevel());
        static::assertEquals(Config::EXECUTE_FASTCGI_FINISH_REQUEST_OFF, $config->getFastcgiFinishRequest());
    }

    public function testJsonConstructor()
    {
        $config = new Config($this->json);

        static::assertEquals('test', $config->getConnector()['type']);
        static::assertEquals('dev', $config->getConnector()['user']);
        static::assertEquals('php-plugin-test', $config->getConnector()['apikey']);
        static::assertEquals(Config::MODE_ON, $config->getMode());
        static::assertEquals(Config::IP_ADDRESS_MANUAL, $config->getIpAddressSrc());
        static::assertEquals('127.0.0.1', $config->getIpAddress());
        static::assertEquals(Config::LOG_LEVEL_NONE, $config->getLogLevel());
        static::assertEquals(Config::OUTPUT_FLUSHING_OFF, $config->getOutputFlushing());
        static::assertEquals(Config::EXECUTE_FASTCGI_FINISH_REQUEST_OFF, $config->getFastcgiFinishRequest());
        static::assertEquals(Config::UOPZ_HOOK_OFF, $config->getUopzHook());
    }

    public function testConnectorConfiguration()
    {
        $config = new Config($this->json);
        BitSensor::configure($config);

        static::assertEquals('dev', TestConnector::$configuration['user']);
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testJsonFastCGIException()
    {
        $json = '
        {
            "executeFastCgi": "on"
        }';

        new Config($json);
    }


}
