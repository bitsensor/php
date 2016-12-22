<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\Context;
use BitSensor\Core\InputContext;
use BitSensor\Core\SessionContext;
use BitSensor\Handler\RequestInputHandler;

class RequestInputHandlerTest extends HandlerTest {

    public function setUp() {
        parent::setUp();

        $_POST = array(
            'foo' => 'bar',
            'bar' => array(
                'baz' => 'qux',
                'quux' => 'corge'
            ),
            'baz' => array(
                'qux',
                'quux'
            )
        );

        $_GET = array(
            'foo' => 'bar',
            'bar' => array(
                'baz' => 'qux',
                'quux' => 'corge'
            ),
            'baz' => array(
                'qux',
                'quux'
            )
        );

        $_COOKIE = array(
            'foo' => 'bar',
            'bar' => array(
                'baz' => 'qux',
                'quux' => 'corge'
            ),
            'baz' => array(
                'qux',
                'quux'
            ),
            'PHPSESSID' => 'test'
        );
    }

    public function tearDown() {
        parent::tearDown();

        $_POST = array();
        $_GET = array();
        $_COOKIE = array();
    }

    public function testHandle() {
        $handler = new RequestInputHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();

        static::assertEquals('test', $contexts[Context::NAME]['php.' . SessionContext::NAME . '.' . SessionContext::SESSION_ID]);

        static::assertEquals('bar', $contexts[InputContext::NAME]['http.post.foo']);
        static::assertEquals('qux', $contexts[InputContext::NAME]['http.post.bar.baz']);
        static::assertEquals('corge', $contexts[InputContext::NAME]['http.post.bar.quux']);
        static::assertEquals('qux', $contexts[InputContext::NAME]['http.post.baz.0']);
        static::assertEquals('quux', $contexts[InputContext::NAME]['http.post.baz.1']);

        static::assertEquals('bar', $contexts[InputContext::NAME]['http.get.foo']);
        static::assertEquals('qux', $contexts[InputContext::NAME]['http.get.bar.baz']);
        static::assertEquals('corge', $contexts[InputContext::NAME]['http.get.bar.quux']);
        static::assertEquals('qux', $contexts[InputContext::NAME]['http.get.baz.0']);
        static::assertEquals('quux', $contexts[InputContext::NAME]['http.get.baz.1']);

        static::assertEquals('bar', $contexts[InputContext::NAME]['http.cookie.foo']);
        static::assertEquals('qux', $contexts[InputContext::NAME]['http.cookie.bar.baz']);
        static::assertEquals('corge', $contexts[InputContext::NAME]['http.cookie.bar.quux']);
        static::assertEquals('qux', $contexts[InputContext::NAME]['http.cookie.baz.0']);
        static::assertEquals('quux', $contexts[InputContext::NAME]['http.cookie.baz.1']);
    }


    public function testHandleUnset() {
        $_POST = array();
        $_GET = array();
        $_COOKIE = array();

        $handler = new RequestInputHandler();
        $handler->handle($this->collector, new Config());

        $contexts = $this->collector->toArray();

        static::assertArrayNotHasKey(Context::NAME, $contexts);
        static::assertArrayNotHasKey(InputContext::NAME, $contexts);
    }

    public function testFlattenPrefix() {
        $prefix = 'bitsensor';

        $input = array(
            'foo' => array(
                'bar' => array(
                    'baz' => 'qux'
                ),
                'baz' => 'qux'
            ),
            'bar' => 'baz'
        );

        $output = array();

        RequestInputHandler::flatten($input, $output, $prefix);

        static::assertEquals('qux', $output['bitsensor.foo.bar.baz']);
        static::assertEquals('qux', $output['bitsensor.foo.baz']);
        static::assertEquals('baz', $output['bitsensor.bar']);
    }

    public function testFlatten() {
        $prefix = '';

        $input = array(
            'foo' => array(
                'bar' => array(
                    'baz' => 'qux'
                ),
                'baz' => 'qux'
            ),
            'bar' => 'baz'
        );

        $output = array();

        RequestInputHandler::flatten($input, $output, $prefix);

        static::assertEquals('qux', $output['foo.bar.baz']);
        static::assertEquals('qux', $output['foo.baz']);
        static::assertEquals('baz', $output['bar']);
    }
}
