<?php

namespace BitSensor\Test\Handler;

use BitSensor\Test\TestBase;

abstract class HandlerTest extends TestBase
{

    protected function tearDown()
    {
        restore_error_handler();
        restore_exception_handler();

        parent::tearDown();
    }

    public abstract function testHandle();

}
