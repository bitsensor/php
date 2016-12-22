<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\CodeError;
use BitSensor\Core\Error;
use BitSensor\Handler\ExceptionHandler;

class ExceptionHandlerTest extends HandlerTest {

    public function testHandle() {
        $message = 'Test Message';
        $code = 17;

        $line = __LINE__ + 1;
        $exception = new \Exception($message, $code);

        ExceptionHandler::handle($exception);

        $contexts = $this->collector->toArray();
        static::assertEquals($message, $contexts[Error::NAME][0][CodeError::ERRSTR]);
        static::assertEquals($code, $contexts[Error::NAME][0][CodeError::ERRNO]);
        static::assertEquals(__FILE__, $contexts[Error::NAME][0][CodeError::ERRFILE]);
        static::assertEquals($line, $contexts[Error::NAME][0][CodeError::ERRLINE]);
        static::assertEquals('Exception', $contexts[Error::NAME][0][CodeError::ERRTYPE]);
    }

}
