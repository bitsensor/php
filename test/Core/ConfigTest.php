<?php

namespace BitSensor\Test\Core;


use BitSensor\Core\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {

    public function testEmptyConstructor() {
        $config = new Config();

        static::assertEmpty($config->getUri());
        static::assertEmpty($config->getUser());
        static::assertEmpty($config->getApiKey());
        static::assertEquals(Config::MODE_DETECTION, $config->getMode());
        static::assertEquals(Config::ACTION_ALLOW, $config->getConnectionFail());
        static::assertEquals(Config::IP_ADDRESS_REMOTE_ADDR, $config->getIpAddressSrc());
        static::assertEmpty($config->getIpAddress());
        static::assertEquals(Config::LOG_LEVEL_ALL, $config->getLogLevel());
    }

    public function testJsonConstructor() {
        $json = '{' .
            '"uri": "http://localhost:8080/",' .
            '"user": "example_user",' .
            '"apiKey": "abcdefghijklmnopqrstuvwxyz",' .
            '"mode": "on",' .
            '"connectionFail": "block",' .
            '"ipAddressSrc": "manual",' .
            '"ipAddress": "127.0.0.1",' .
            '"logLevel": "none"' .
            '}';
        $config = new Config($json);

        static::assertEquals('http://localhost:8080/', $config->getUri());
        static::assertEquals('example_user', $config->getUser());
        static::assertEquals('abcdefghijklmnopqrstuvwxyz', $config->getApiKey());
        static::assertEquals(Config::MODE_ON, $config->getMode());
        static::assertEquals(Config::ACTION_BLOCK, $config->getConnectionFail());
        static::assertEquals(Config::IP_ADDRESS_MANUAL, $config->getIpAddressSrc());
        static::assertEquals('127.0.0.1', $config->getIpAddress());
        static::assertEquals(Config::LOG_LEVEL_NONE, $config->getLogLevel());
    }
}
