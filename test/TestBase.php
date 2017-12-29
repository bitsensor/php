<?php

namespace BitSensor\Test;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use PHPUnit_Framework_TestCase;
use Proto\Datapoint;

/**
 * Class DatabaseTestBase contains test suite setup and basic test cases.
 * @package BitSensor\Test\Hook
 */
abstract class TestBase extends PHPUnit_Framework_TestCase
{
    /** @var BitSensor $bitSensor */
    protected $bitSensor;
    /** @var Datapoint $datapoint */
    protected $datapoint;

    protected function setUp()
    {
        global $bitSensor;
        $bitSensor = new BitSensor();
        $this->bitSensor = &$bitSensor;

        $config = new Config();
        //Skip curl request etc.
        $config->setSkipShutdownHandler(true);
        $this->bitSensor->config($config);

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;
    }

    protected function tearDown()
    {
        unset($this->datapoint);
        unset($this->bitSensor);
    }
}