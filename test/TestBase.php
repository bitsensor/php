<?php

namespace BitSensor\Test;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Handler\AfterRequestHandler;
use PHPUnit_Framework_TestCase;
use Proto\Datapoint;

/**
 * Class DatabaseTestBase contains test suite setup and basic test cases.
 * @package BitSensor\Test\Hook
 */
abstract class TestBase extends PHPUnit_Framework_TestCase
{
    /** @var BitSensor $bitSensor */
    /** @var Datapoint $datapoint */
    protected $bitSensor;
    protected $datapoint;


    protected function setUp()
    {
        global $bitSensor;
        $bitSensor = new BitSensor(new Config());
        $this->bitSensor = &$bitSensor;

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;
    }

    protected function tearDown()
    {
        unset($this->datapoint);
        unset($this->bitSensor);
    }

    /**
     * @see AfterRequestHandler
     */
    public static function tearDownAfterClass()
    {
        // Disable apiConnector
        global $bitsensorNoShutdownHandler;
        $bitsensorNoShutdownHandler = true;
    }
}