<?php

namespace BitSensor\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Test\TestBase;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Proto\Error;
use Psr\Log\LogLevel;


class PsrLogHandlerTest extends TestBase
{

    public function testLog()
    {
        $handler = new PsrLogHandler();
        $handler->log(LogLevel::NOTICE, 'message of level notice');

        /** @var Error $err */
        $err = BitSensor::getDatapoint()->getErrors()[0];

        self::assertEquals(LogLevel::NOTICE, $err->getType());
        self::assertEquals($err->getDescription(), 'message of level notice');
    }

    public function testWarning()
    {
        $handler = new PsrLogHandler();
        $handler->warning('message of level warning');

        /** @var Error $err */
        $err = BitSensor::getDatapoint()->getErrors()[0];

        self::assertEquals(LogLevel::WARNING, $err->getType());
        self::assertEquals($err->getDescription(), 'message of level warning');
    }

    public function testMonolog()
    {
        $log = new Logger('mononlog-test');

        // Add the BitSensor PsrLogHandler
        $log->pushHandler(new PsrHandler(new PsrLogHandler()));
        $log->warn("monolog has been added");

        /** @var Error $err */
        $err = BitSensor::getDatapoint()->getErrors()[0];

        self::assertEquals(LogLevel::WARNING, $err->getType());
        self::assertEquals($err->getDescription(), 'monolog has been added');
    }
}
