<?php

namespace BitSensor\Test\Handler;

use BitSensor\Test\TestBase;

abstract class HandlerTest extends TestBase
{

    protected function tearDown()
    {
        restore_error_handler();
        restore_exception_handler();

        unset($this->datapoint);
        unset($this->bitSensor);
    }

    public static function tearDownAfterClass()
    {
        global $bitsensorNoShutdownHandler;
        $bitsensorNoShutdownHandler = true;
    }

    public abstract function testHandle();

}
