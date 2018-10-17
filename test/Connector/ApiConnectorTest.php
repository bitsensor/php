<?php

namespace BitSensor\Test\Connector;


use BitSensor\Connector\ApiConnector;
use BitSensor\Exception\ApiException;
use Proto\Datapoint;

class ApiConnectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws ApiException
     */
    public function testConstruction()
    {
        $data = new Datapoint();

        $connector = new ApiConnector();
        $connector->setUser("dev");
        $connector->setApiKey("php-plugin-test");
        $connector->close($data);

        static::assertTrue($connector instanceof ApiConnector);
    }

}
