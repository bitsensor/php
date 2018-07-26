<?php

namespace BitSensor\Test\Core;


use BitSensor\Blocking\Action\TestAction;
use BitSensor\Blocking\Blocking;
use BitSensor\Connector\TestConnector;
use BitSensor\Core\BitSensor;

class BitSensorConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonConstructor()
    {
        BitSensor::configure([
            'logLevel' => 'none',
            'connector' => [
                'type' => 'test'
            ],
            'blocking' => [
                'action' => 'test'
            ]
        ]);

        static::assertEquals(TestConnector::class, get_class(BitSensor::getConnector()));
        static::assertEquals(BitSensor::MODE_IDS, BitSensor::getMode());
        static::assertEquals('none', BitSensor::getLogLevel());
        static::assertEquals(TestAction::class, get_class(Blocking::getAction()));
    }
}
