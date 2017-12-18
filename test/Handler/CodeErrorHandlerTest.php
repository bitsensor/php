<?php

namespace BitSensor\Test\Handler;

use BitSensor\Handler\CodeErrorHandler;
use Proto\Datapoint;
use Proto\Error;

class CodeErrorHandlerTest extends HandlerTest
{
    public function testHandle()
    {
        $message = 'Test Message';
        $code = 17;
        $file = 'test.php';
        $line = 5;

        CodeErrorHandler::handle($code, $message, $file, $line, NULL);

        /** @var Datapoint $datapoint */
        global $datapoint;

        self::assertEquals(1, $datapoint->getErrors()->count(), "Datapoint must contain 1 error.");

        /** @var Error $err */
        $err = $datapoint->getErrors()[0];

        self::assertEquals($err->getCode(), $code);
        self::assertEquals($err->getDescription(), $message);
        self::assertEquals($err->getLocation(), $file);
        self::assertEquals($err->getLine(), $line);
    }
}
