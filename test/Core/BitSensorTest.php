<?php

namespace BitSensor\Test\Core;


use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use Proto\Datapoint;
use Proto\Error;

class BitSensorTest extends \PHPUnit_Framework_TestCase
{

    public static $proofOfInvocation = false;


    /** @var BitSensor $bitSensor */
    private $bitSensor;

    /** @var Datapoint $datapoint */
    private $datapoint;

    protected function setUp()
    {
        global $bitSensor;
        $bitSensor = new BitSensor(new Config());
        $this->bitSensor = &$bitSensor;

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;
    }

    protected function tearDown()
    {
        restore_error_handler();
        restore_exception_handler();

        global $datapoint;
        unset($datapoint);
    }

    public static function tearDownAfterClass()
    {
        global $bitsensorNoShutdownHandler;
        $bitsensorNoShutdownHandler = true;
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
     * @expectedException PHPUnit_Framework_Error
     */
    public function testOldErrorHandlerSet()
    {
        self::assertEquals('PHPUnit_Util_ErrorHandler', $this->bitSensor->errorHandler[0]);

        trigger_error('test');
        self::assertTrue(self::$proofOfInvocation);
        self::$proofOfInvocation = false;
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testOldExceptionHandlerSet()
    {
        set_exception_handler(array($this, 'callback'));
        $this->bitSensor = new BitSensor(new Config());
        self::assertEquals(__CLASS__, $this->bitSensor->exceptionHandler[0]);

        trigger_error('test');
        self::assertTrue(self::$proofOfInvocation);
        self::$proofOfInvocation = false;
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
