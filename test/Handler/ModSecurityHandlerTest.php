<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\Context;
use BitSensor\Core\ModSecurityContext;
use BitSensor\Handler\ModSecurityHandler;

class ModSecurityHandlerTest extends HandlerTest {

    public function setUp() {
        parent::setUp();

        $_SERVER['HTTP_X_WAF_EVENTS'] = 'foo';
        $_SERVER['HTTP_X_WAF_SCORE'] = 5;
    }

    public function tearDown() {
        parent::tearDown();

        unset(
            $_SERVER['HTTP_X_WAF_EVENTS'],
            $_SERVER['HTTP_X_WAF_SCORE']
        );
    }

    public function testHandle() {
        $handler = new ModSecurityHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();
        static::assertEquals('foo', $contexts[Context::NAME][ModSecurityContext::NAME . '.' . ModSecurityContext::WAF_EVENTS]);
        static::assertEquals(5, $contexts[Context::NAME][ModSecurityContext::NAME . '.' . ModSecurityContext::WAF_SCORE]);
    }

    public function testHandleUnset() {
        unset(
            $_SERVER['HTTP_X_WAF_EVENTS'],
            $_SERVER['HTTP_X_WAF_SCORE']
        );

        $handler = new ModSecurityHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();
        static::assertArrayNotHasKey(Context::NAME, $contexts);
    }

}
