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

    /**
     * @see AfterRequestHandler
     */
    public static function setUpBeforeClass()
    {
        // Disable apiConnector
        global $bitsensorNoShutdownHandler;
        $bitsensorNoShutdownHandler = true;
    }

    protected function setUp()
    {
        global $bitSensor;
        $bitSensor = new BitSensor();
        $this->bitSensor = &$bitSensor;
        $this->bitSensor->config(new Config());

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