<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\EndpointContext;
use BitSensor\Handler\InterfaceHandler;

class InterfaceHandlerTest extends HandlerTest {

    public function testHandle() {
        $handler = new InterfaceHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();
        static::assertTrue($contexts[EndpointContext::NAME][EndpointContext::CLI]);
        static::assertEquals('cli', $contexts[EndpointContext::NAME][EndpointContext::SAPI]);
    }
}
