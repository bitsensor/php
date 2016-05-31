<?php


namespace BitSensor\Test\Handler;


use BitSensor\Core\Collector;

abstract class HandlerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Collector
     */
    protected $collector;

    protected function setUp() {
        global $collector;
        $collector = new Collector();
        $this->collector = &$collector;
    }


    protected function tearDown() {
        global $collector;
        unset($collector);
    }

    public abstract function testHandle();

}
