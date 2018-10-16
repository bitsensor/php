<?php

namespace BitSensor\Test\Handler;

use BitSensor\Core\BitSensor;
use BitSensor\Handler\ExceptionHandler;
use Proto\Error;

class ExceptionHandlerTest extends HandlerTest
{

    public function testHandle()
    {
        $message = 'Test Message';
        $code = 17;
        $line = __LINE__ + 1;
        $exception = new \Exception($message, $code);
        $expectedContext = explode(PHP_EOL, $exception->getTraceAsString());


        ExceptionHandler::handle($exception);

        self::assertEquals(1, BitSensor::getDatapoint()->getErrors()->count(), "Datapoint must contain 1 error.");

        /** @var Error $err */
        $err = BitSensor::getDatapoint()->getErrors()[0];

        echo print_r($err->getContext()[0], true);
        echo print_r($expectedContext, true);

        self::assertEquals($err->getCode(), $code);
        self::assertEquals($err->getDescription(), $message);
        self::assertEquals($err->getLine(), $line);
        self::assertEquals($err->getContext()[0], $expectedContext);
    }

}
