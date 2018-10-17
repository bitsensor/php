<?php

namespace BitSensor\Test;

use BitSensor\Blocking\Blocking;
use BitSensor\Core\BitSensor;
use PHPUnit_Framework_TestCase;

/**
 * Class DatabaseTestBase contains test suite setup and basic test cases.
 * @package BitSensor\Test\Hook
 */
abstract class TestBase extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $datapoint = self::getPrivateDatapoint();
        $datapoint->setValue(new \Proto\Datapoint());

        BitSensor::setEnbaleShutdownHandler(false);
        BitSensor::createConnector('noop');
        Blocking::setEnabled(false);
    }

    private static function getPrivateDatapoint()
    {
        $class = new \ReflectionClass('BitSensor\Core\BitSensor');
        $datapoint = $class->getProperty('datapoint');
        $datapoint->setAccessible(true);

        return $datapoint;
    }
}