<?php

namespace Core;


use BitSensor\Core\Error;

class ErrorTest extends \PHPUnit_Framework_TestCase {

    public function testToArray() {
        $message = 'Test Message';
        $code = 17;
        $type = 'Test Type';

        $error = new Error($message, $code, $type);

        $array = $error->toArray();
        static::assertArrayHasKey(Error::ERRSTR, $array);
        static::assertArrayHasKey(Error::ERRNO, $array);
        static::assertArrayHasKey(Error::ERRTYPE, $array);

        static::assertEquals($message, $array[Error::ERRSTR]);
        static::assertEquals($code, $array[Error::ERRNO]);
        static::assertEquals($type, $array[Error::ERRTYPE]);
    }

}