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
        BitSensor::init();
        BitSensor::setEnbaleShutdownHandler(false);
        BitSensor::createConnector('noop');
        Blocking::setEnabled(false);
    }
}