<?php

namespace BitSensor\Test\Handler\Blocking;


use BitSensor\Blocking\Action\BlockingpageAction;
use PHPUnit_Framework_TestCase;
use Proto\Datapoint;

class TestBlockingPageHandler extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error
     */
    function testBlockingPageHandlerDies()
    {
        BlockingpageAction::setUser('dev');
        $blockingpageAction = new BlockingpageAction();
        $blockingpageAction->block(new Datapoint(), "1");

        $this->expectOutputString("<title>Blocked by BitSensor</title>");
    }
}