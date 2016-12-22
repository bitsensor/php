<?php

namespace BitSensor\Test\Handler;


use BitSensor\Core\CodeError;
use BitSensor\Core\Error;
use BitSensor\Handler\CodeErrorHandler;

class CodeErrorHandlerTest extends HandlerTest {

    public function testHandle() {
        $message = 'Test Message';
        $code = 17;
        $file = 'test.php';
        $line = 5;

        CodeErrorHandler::handle($code, $message, $file, $line, NULL);

        $contexts = $this->collector->toArray();
        static::assertEquals($message, $contexts[Error::NAME][0][CodeError::ERRSTR]);
        static::assertEquals($code, $contexts[Error::NAME][0][CodeError::ERRNO]);
        static::assertEquals($file, $contexts[Error::NAME][0][CodeError::ERRFILE]);
        static::assertEquals($line, $contexts[Error::NAME][0][CodeError::ERRLINE]);
        static::assertEquals('Code', $contexts[Error::NAME][0][CodeError::ERRTYPE]);
    }

}
