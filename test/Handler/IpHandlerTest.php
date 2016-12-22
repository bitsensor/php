<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\Context;
use BitSensor\Core\IpContext;
use BitSensor\Handler\IpHandler;

class IpHandlerTest extends HandlerTest {

    public function setUp() {
        parent::setUp();

        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.2';
    }

    public function tearDown() {
        parent::tearDown();

        unset(
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_X_FORWARDED_FOR']
        );
    }

    public function testHandle() {
        $handler = new IpHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();
        static::assertEquals('127.0.0.1', $contexts[Context::NAME][IpContext::NAME]);
    }

    public function testHandleForwarded() {
        $config = new Config();
        $config->setIpAddressSrc(Config::IP_ADDRESS_X_FORWARDED_FOR);

        $handler = new IpHandler();
        $handler->handle($this->collector, $config);

        $contexts = $this->collector->toArray();
        static::assertEquals('127.0.0.2', $contexts[Context::NAME][IpContext::NAME]);
    }

    public function testHandleManual() {
        $config = new Config();
        $config->setIpAddressSrc(Config::IP_ADDRESS_MANUAL);
        $config->setIpAddress('127.0.0.3');

        $handler = new IpHandler();
        $handler->handle($this->collector, $config);

        $contexts = $this->collector->toArray();
        static::assertEquals('127.0.0.3', $contexts[Context::NAME][IpContext::NAME]);
    }

    public function testHandleUnset() {
        unset(
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_X_FORWARDED_FOR']
        );

        $handler = new IpHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();
        static::assertArrayNotHasKey(Context::NAME, $contexts);
    }

}
