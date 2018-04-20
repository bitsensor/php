<?php

namespace BitSensor\Test\Connector;


use BitSensor\Connector\TestConnector;
use BitSensor\Core\BitSensor;
use BitSensor\Test\TestBase;

class TestConnectorTest extends TestBase
{
    /**
     * @throws \BitSensor\Exception\ApiException
     */
    public function testSanity()
    {
        BitSensor::createConnector('test');
        BitSensor::finish();

        static::assertNotEmpty(TestConnector::$datapoint, "Datapoint should not be empty");
    }
}