<?php

namespace BitSensor\Test\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Core\EndpointConstants;
use BitSensor\Handler\InterfaceHandler;

class InterfaceHandlerTest extends HandlerTest
{

    public function testHandle()
    {
        $handler = new InterfaceHandler();
        $handler->handle(BitSensor::getDatapoint(), new Config());

        $endpoint = BitSensor::getDatapoint()->getEndpoint();

        self::assertEquals('true', $endpoint[EndpointConstants::CLI]);
        self::assertEquals('cli', $endpoint[EndpointConstants::SAPI]);
    }
}
