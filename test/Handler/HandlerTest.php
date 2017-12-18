<?php

namespace BitSensor\Test\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use Proto\Datapoint;

abstract class HandlerTest extends \PHPUnit_Framework_TestCase
{
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
        unset($datapoint);
        unset($bitSensor);

        set_error_handler(null);
        set_exception_handler(null);
    }

    public abstract function testHandle();

}
