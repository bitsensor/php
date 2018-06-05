<?php

namespace BitSensor\Test\Handler\Blocking;


use BitSensor\Blocking\Action\TestAction;
use BitSensor\Blocking\Blocking;
use PHPUnit_Framework_TestCase;
use Proto\Datapoint;

class BlockingTest extends PHPUnit_Framework_TestCase
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Blocking::configure([
            "enabled" => "true",
            "filePath" => __DIR__ . "/blocks.json",
            "action" => "test"
        ]);
    }

    function testBlockingConfiguration()
    {
        Blocking::setAction(new TestAction());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User blocked by BitSensor for block with id AWJsa-K8OgCnroTMO6dT
     */
    function testBlockingHandlerActionIsTest()
    {
        $this->testForDatapointThatShouldBeBlocked();
        static::assertNotEmpty(TestAction::$datapoint, "TestingAction is used");
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User blocked by BitSensor for block with id AWJsa-K8OgCnroTMO6dT
     */
    function testForDatapointThatShouldBeBlocked()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.1";
        $underTest->getContext()["http.userAgent"] = "Mozilla";

        $blocking = new Blocking();
        $blocking->handle($underTest);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User blocked by BitSensor for block with id AWJsa-K8OgCnroTMO6dT
     */
    function testForDatapointForAdditionalFieldThatShouldStillBeBlocked()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.1";
        $underTest->getContext()["http.userAgent"] = "Mozilla";
        $underTest->getContext()["http.additionalField"] = "Only set in datapopint, not required for the block";

        $blocking = new Blocking();
        $blocking->handle($underTest);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User blocked by BitSensor for block with id AWJsa-K8OgCnroTMO6dT
     */
    function testForDatapointInBlockedDatapointsArrayThatShouldBeBlocked()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.2";
        $underTest->getContext()["http.userAgent"] = "Chrome";

        $blocking = new Blocking();
        $blocking->handle($underTest);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User blocked by BitSensor for block with id AWJsa-K8OgCnroTMO6dU
     */
    function testForDatapointInBlocksArrayThatShouldBeBlocked()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.3";
        $underTest->getContext()["http.userAgent"] = "Chrome";

        $blocking = new Blocking();
        $blocking->handle($underTest);
    }

    function testForDatapointThatShouldNotBeBlockedDueToNotAllFieldsMatching()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.1";

        $blocking = new Blocking();
        $blocking->handle($underTest);

        static::assertArrayNotHasKey('blocked', $underTest->getEndpoint());
    }

    function testForDatapointThatShouldNotBeBlockedDueToNoFieldsMatching()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.4";

        $blocking = new Blocking();
        $blocking->handle($underTest);

        static::assertArrayNotHasKey('blocked', $underTest->getEndpoint());
    }

    function testForEmptyDatapoint()
    {
        $underTest = new Datapoint();

        $blocking = new Blocking();
        $blocking->handle($underTest);

        static::assertArrayNotHasKey('blocked', $underTest->getEndpoint());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage User blocked by BitSensor for block with id AWJsa-K8OgCnroTMO6dT
     */
    function testDatapointContainsEndpointBlockedFields()
    {
        $underTest = new Datapoint();
        $underTest->getContext()["ip"] = "172.17.0.1";
        $underTest->getContext()["http.userAgent"] = "Mozilla";

        $blocking = new Blocking();
        $blocking->handle($underTest);

        static::assertEquals("AWJsa-K8OgCnroTMO6dT", $underTest->getEndpoint()['blocking.id']);
        static::assertEquals("true", $underTest->getEndpoint()['blocked']);
    }

}