<?php

namespace BitSensor\Test\Handler;

use Proto\Datapoint;

abstract class HandlerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Datapoint
     */
    protected $datapoint;

    protected function setUp()
    {
        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;
    }


    protected function tearDown()
    {
        global $datapoint;
        unset($datapoint);
    }

    public abstract function testHandle();

}
