<?php

namespace BitSensor\Test\Core;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Handler\CodeErrorHandler;
use BitSensor\Handler\ExceptionHandler;
use BitSensor\Test\TestBase;
use PHPUnit_Util_ErrorHandler;
use Proto\Datapoint;
use Proto\Error;
use Proto\GeneratedBy;

class BitSensorTest extends TestBase
{

    public static $proofOfInvocation = false;

    protected function tearDown()
    {
        unset($this->datapoint);
        unset($this->bitSensor);
    }

    public function testAddContext()
    {
        $ip = '127.0.0.1';

        $this->bitSensor->putContext('ip', $ip);

        $context = $this->datapoint->getContext();

        self::assertEquals($ip, $context['ip']);
        self::assertEmpty($this->datapoint->getInput());
        self::assertEmpty($this->datapoint->getErrors());
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

        $this->bitSensor->putEndpoint('ip', $ip);
        $endpoint = $this->datapoint->getEndpoint();

        self::assertEquals($ip, $endpoint['ip']);
        self::assertEmpty($this->datapoint->getContext());
        self::assertEmpty($this->datapoint->getErrors());
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

        $this->bitSensor->addError($error);
        $errors = $this->datapoint->getErrors();

        self::assertEquals($error, $errors[0]);
        self::assertEquals(GeneratedBy::PLUGIN, $errors[0]->getGeneratedby());
        self::assertEmpty($this->datapoint->getInput());
        self::assertEmpty($this->datapoint->getContext());
    }

    public function testAddInput()
    {
        $ip = '127.0.0.1';

        $this->bitSensor->putInput('ip', $ip);
        $input = $this->datapoint->getInput();

        self::assertEquals($ip, $input['ip']);
        self::assertEmpty($this->datapoint->getContext());
        self::assertEmpty($this->datapoint->getErrors());
    }

}
