<?php

namespace BitSensor\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use PHPUnit_Framework_Error_Warning;
use PHPUnit_Framework_TestCase;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;

/**
 * Class PsrLogHandlerBufferTest
 * @package BitSensor\Handler
 */
class PsrLogHandlerBufferTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        PsrLogHandler::clearBuffer();
        PsrLogHandler::setBufferSize(50);
    }

    public function testLogBuffer()
    {
        $log = $this->setLogger();
        $log->warn("logging before initialisation");

        self::assertEquals(1, count(PsrLogHandler::getBuffer()));
        self::assertEquals(false, PsrLogHandler::isOverflown());
    }

    public function testLogBufferOverflown()
    {
        PsrLogHandler::setBufferSize(0);

        $log = $this->setLogger();
        $log->warn("logging before initialisation");

        self::assertEquals(0, count(PsrLogHandler::getBuffer()));
        self::assertEquals(true, PsrLogHandler::isOverflown());
    }

    /**
     * @return Logger
     */
    public function setLogger()
    {
        $log = new Logger('mononlog-test');

        // Add the BitSensor PsrLogHandler
        $log->pushHandler(new PsrHandler(new PsrLogHandler()));
        return $log;
    }

    /**
     *
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testOverflownWarning()
    {
        PsrLogHandler::setBufferSize(0);

        $log = new Logger('mononlog-test');

        // Add the BitSensor PsrLogHandler
        $psrLogHandler = new PsrLogHandler();
        $log->pushHandler(new PsrHandler($psrLogHandler));
        $log->warn("logging before initialisation with no buffer");

        self::assertTrue(PsrLogHandler::isOverflown());

        global $bitSensor;
        $bitSensor = new BitSensor();

        global $config;
        $config = new Config('{"uri": "http://dev.bitsensor.io:8080/"}');
        $config->setSkipShutdownHandler(true);
        $bitSensor->config($config);

        AfterRequestHandler::handle($bitSensor);
    }
}
