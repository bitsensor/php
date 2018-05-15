<?php

namespace BitSensor\Test\Connector;


use BitSensor\Connector\FileConnector;
use BitSensor\Core\BitSensor;

class FileConnectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @throws ApiException
     */
    public function testConstruction()
    {
        BitSensor::putContext("test", "true");

        $connector = new FileConnector();
        $connector::setFilename("test.log");
        $connector->close(BitSensor::getDatapoint());

        static::assertRegExp("{.*test.*true.*}\n", file_get_contents("test.log"));
        unlink("test.log");
    }

}
