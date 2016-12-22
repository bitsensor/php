<?php

namespace BitSensor\Test\Core;


use BitSensor\Core\CodeError;

class CodeErrorTest extends \PHPUnit_Framework_TestCase {

    public function testToArray() {
        $code = 17;
        $message = 'Test Message';
        $file = 'test.php';
        $line = 5;
        $context = 'Context';
        $type = 'Code';

        $error = new CodeError($code, $message, $file, $line, $context, $type);

        $array = $error->toArray();
        static::assertArrayHasKey(CodeError::ERRNO, $array);
        static::assertArrayHasKey(CodeError::ERRSTR, $array);
        static::assertArrayHasKey(CodeError::ERRFILE, $array);
        static::assertArrayHasKey(CodeError::ERRLINE, $array);
        static::assertArrayHasKey(CodeError::ERRCONTEXT, $array);
        static::assertArrayHasKey(CodeError::ERRTYPE, $array);

        static::assertEquals($code, $array[CodeError::ERRNO]);
        static::assertEquals($message, $array[CodeError::ERRSTR]);
        static::assertEquals($file, $array[CodeError::ERRFILE]);
        static::assertEquals($line, $array[CodeError::ERRLINE]);
        static::assertEquals($context, $array[CodeError::ERRCONTEXT]);
        static::assertEquals($type, $array[CodeError::ERRTYPE]);
    }

}