<?php

namespace BitSensor\Test\Core;


use BitSensor\Core\Collector;
use BitSensor\Core\Context;
use BitSensor\Core\EndpointContext;
use BitSensor\Core\Error;
use BitSensor\Core\InputContext;
use BitSensor\Core\IpContext;

class CollectorTest extends \PHPUnit_Framework_TestCase {

    public function testAddContext() {
        $ip = '127.0.0.1';

        $collector = new Collector();
        $collector->addContext(new IpContext($ip));

        $contexts = $collector->toArray();
        static::assertEquals($ip, $contexts[Context::NAME][IpContext::NAME]);
        static::assertArrayNotHasKey(InputContext::NAME, $contexts);
        static::assertArrayNotHasKey(Error::NAME, $contexts);
    }

    public function testAddEndpointContext() {
        $ip = '127.0.0.1';

        $collector = new Collector();
        $collector->addEndpointContext(new IpContext($ip));

        $contexts = $collector->toArray();
        static::assertEquals($ip, $contexts[EndpointContext::NAME][IpContext::NAME]);
        static::assertArrayNotHasKey(InputContext::NAME, $contexts);
        static::assertArrayNotHasKey(Error::NAME, $contexts);
    }

    public function testAddError() {
        $message = 'Test Message';
        $code = 17;
        $type = 'Test Type';
        $error = new Error($message, $code, $type);

        $collector = new Collector();
        $collector->addError($error);

        $contexts = $collector->toArray();
        static::assertArrayHasKey(Error::NAME, $contexts);
        static::assertEquals($error->toArray(), $contexts[Error::NAME][0]);
        static::assertArrayNotHasKey(InputContext::NAME, $contexts);
    }

    public function testAddInput() {
        $ip = '127.0.0.1';

        $collector = new Collector();
        $collector->addInput(new IpContext($ip));

        $contexts = $collector->toArray();
        static::assertArrayHasKey(InputContext::NAME, $contexts);
        static::assertEquals($ip, $contexts[InputContext::NAME][IpContext::NAME]);
        static::assertArrayNotHasKey(Error::NAME, $contexts);
    }

}
