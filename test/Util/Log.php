<?php

namespace BitSensor\Test\Util;

use BitSensor\Util\Log;

class LogTest extends TestBase {

    function testLogShouldEcho(){
        Log::setEnabled(true);
        Log::d("test");
        $this->expectOutputString('test');
    }

}