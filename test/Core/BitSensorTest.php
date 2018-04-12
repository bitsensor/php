<?php

namespace BitSensor\Test\Core;

use BitSensor\Core\BitSensor;
use BitSensor\Test\TestBase;
use PHPUnit_Util_ErrorHandler;
use Proto\Error;
use Proto\GeneratedBy;

class BitSensorTest extends TestBase
{

    public static $proofOfInvocation = false;

    public function testAddContext()
    {
        $ip = '127.0.0.1';

        BitSensor::putContext('ip', $ip);

        $context = BitSensor::getDatapoint()->getContext();

        self::assertEquals($ip, $context['ip']);
        self::assertEmpty(BitSensor::getDatapoint()->getInput());
        self::assertEmpty(BitSensor::getDatapoint()->getErrors());
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testOldErrorHandlerSet()
    {
        self::assertEquals(PHPUnit_Util_ErrorHandler::class, BitSensor::$errorHandler[0]);

        trigger_error('test', E_USER_WARNING);
    }

    public static function callback($ex)
    {
        BitSensorTest::$proofOfInvocation = true;
    }

    public function testAddEndpointContext()
    {
        $ip = '127.0.0.1';

        BitSensor::putEndpoint('ip', $ip);
        $endpoint = BitSensor::getDatapoint()->getEndpoint();

        self::assertEquals($ip, $endpoint['ip']);
    }

    public function testAddError()
    {
        $message = 'Test Message';
        $code = 17;
        $type = 'Test Type';

        $error = new Error();
        $error->setCode($code);
        $error->setDescription($message);
        $error->setType($type);

        BitSensor::addError($error);
        $errors = BitSensor::getDatapoint()->getErrors();

        self::assertEquals($error, $errors[0]);
        self::assertEquals(GeneratedBy::PLUGIN, $errors[0]->getGeneratedby());
    }

    public function testAddInput()
    {
        $ip = '127.0.0.1';

        BitSensor::putInput('ip', $ip);
        $input = BitSensor::getDatapoint()->getInput();

        self::assertEquals($ip, $input['ip']);
    }

}
