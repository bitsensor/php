<?php

namespace BitSensor\Test\Connector;

use BitSensor\Connector\ErrorlogConnector;
use BitSensor\Core\BitSensor;

class ErrorlogConnectorTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruction()
    {
        BitSensor::putContext("test", "true");

        $connector = new ErrorlogConnector();

        ini_set('error_log', 'php://output');
        $connector->close(BitSensor::getDatapoint());
    }
}