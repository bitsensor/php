<?php

namespace BitSensor\Test;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
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

        $config = $this->buildDefaultConfig();

        BitSensor::configure($config);
    }

    protected function tearDown()
    {

    }

    /**
     * @return Config
     */
    protected function buildDefaultConfig()
    {
        $config = new Config();
        $config->setSkipShutdownHandler(true);
        $config->setConnector('noop');
        return $config;
    }
}