<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Handler\IpHandler;

class IpHandlerTest extends HandlerTest
{

    public function setUp()
    {
        parent::setUp();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.2, 8.8.8.8';
    }

    public function tearDown()
    {
        parent::tearDown();

        unset(
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_X_FORWARDED_FOR']
        );
    }

    public function testHandle()
    {
        $handler = new IpHandler();
        $handler->handle(BitSensor::getDatapoint(), new Config());

        $context = BitSensor::getDatapoint()->getContext();
        self::assertEquals('127.0.0.1', $context['ip']);
    }

    public function testHandleForwarded()
    {
        $handler = new IpHandler();
        IpHandler::setIpAddressSrc(Config::IP_ADDRESS_X_FORWARDED_FOR);
        $handler->handle(BitSensor::getDatapoint());

        $context = BitSensor::getDatapoint()->getContext();
        self::assertEquals('127.0.0.2', $context['ip']);
    }

    public function testHandleManual()
    {

        $handler = new IpHandler();
        IpHandler::setIpAddressSrc(Config::IP_ADDRESS_MANUAL);
        IpHandler::setIp('127.0.0.3');
        $handler->handle(BitSensor::getDatapoint());

        $context = BitSensor::getDatapoint()->getContext();
        self::assertEquals('127.0.0.3', $context['ip']);
    }
}
