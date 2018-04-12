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
        $config = parent::buildDefaultConfig();
        $config->setConnector('test');
        BitSensor::configure($config);

        BitSensor::finish();
        static::assertNotEmpty(TestConnector::$datapoint, "Datapoint should not be empty");
    }
}