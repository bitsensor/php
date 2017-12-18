<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\Config;
use BitSensor\Core\SessionContext;
use BitSensor\Handler\RequestInputHandler;

class RequestInputHandlerTest extends HandlerTest
{

    public function setUp()
    {
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

    public function tearDown()
    {
        parent::tearDown();

        $_POST = array();
        $_GET = array();
        $_COOKIE = array();
    }

    public function testHandle()
    {
        $handler = new RequestInputHandler();
        $handler->handle($this->datapoint, new Config());

        $context = $this->datapoint->getContext();
        $input = $this->datapoint->getInput();

        static::assertEquals('test', $context['php.' . SessionContext::NAME . '.' . SessionContext::SESSION_ID]);

        static::assertEquals('bar', $input['post.foo']);
        static::assertEquals('qux', $input['post.bar.baz']);
        static::assertEquals('corge', $input['post.bar.quux']);
        static::assertEquals('qux', $input['post.baz.0']);
        static::assertEquals('quux', $input['post.baz.1']);

        static::assertEquals('bar', $input['get.foo']);
        static::assertEquals('qux', $input['get.bar.baz']);
        static::assertEquals('corge', $input['get.bar.quux']);
        static::assertEquals('qux', $input['get.baz.0']);
        static::assertEquals('quux', $input['get.baz.1']);

        static::assertEquals('bar', $input['cookie.foo']);
        static::assertEquals('qux', $input['cookie.bar.baz']);
        static::assertEquals('corge', $input['cookie.bar.quux']);
        static::assertEquals('qux', $input['cookie.baz.0']);
        static::assertEquals('quux', $input['cookie.baz.1']);
    }

    public function testFlattenPrefix()
    {
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

    public function testFlatten()
    {
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
