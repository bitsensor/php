<?php

namespace BitSensor\Test\Handler;

use BitSensor\Handler\ExceptionHandler;
use Proto\Datapoint;
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

        /** @var Datapoint $datapoint */
        global $datapoint;

        self::assertEquals(1, $datapoint->getErrors()->count(), "Datapoint must contain 1 error.");

        /** @var Error $err */
        $err = $datapoint->getErrors()[0];

        self::assertEquals($err->getCode(), $code);
        self::assertEquals($err->getDescription(), $message);
        self::assertEquals($err->getLine(), $line);
        self::assertEquals($err->getContext(), $expectedContext);
    }

}
