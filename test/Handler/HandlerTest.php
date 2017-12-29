<?php

namespace BitSensor\Test\Handler;

use BitSensor\Test\TestBase;

abstract class HandlerTest extends TestBase
{

    protected function tearDown()
    {
        parent::tearDown();
    }

    public abstract function testHandle();

}
