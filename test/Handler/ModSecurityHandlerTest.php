<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\Context;
use BitSensor\Core\ModSecurityContext;
use BitSensor\Handler\ModSecurityHandler;

class ModSecurityHandlerTest extends HandlerTest
{

    public function setUp()
    {
        parent::setUp();

        $_SERVER['HTTP_X_WAF_EVENTS'] = 'foo';
        $_SERVER['HTTP_X_WAF_SCORE'] = 5;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset(
            $_SERVER['HTTP_X_WAF_EVENTS'],
            $_SERVER['HTTP_X_WAF_SCORE']
        );
    }

    public function testHandle()
    {
        $handler = new ModSecurityHandler();
        $handler->handle($this->datapoint, new Config());

        $context = $this->datapoint->getContext();
        static::assertEquals('foo', $context[ModSecurityContext::NAME . '.' . ModSecurityContext::WAF_EVENTS]);
        static::assertEquals('5', $context[ModSecurityContext::NAME . '.' . ModSecurityContext::WAF_SCORE]);
    }
}
