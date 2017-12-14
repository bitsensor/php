<?php

namespace BitSensor\Test\Hook;

use Proto\Datapoint;
use Proto\Invocation_SQLInvocation;

abstract class DatabaseTestBase extends \PHPUnit_Framework_TestCase
{
    /** @var Datapoint $datapoint */
    protected $datapoint;
    protected $host;
    protected $pass;

    /**
     * DataBaseTestBase constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->host = getenv('BITSENSOR_MYSQL_HOST') ?: 'localhost';
        $this->pass = getenv('MYSQL_ROOT_PASSWORD') ?: '';
    }

    protected function setUp()
    {
        if (!extension_loaded('uopz')) {
            self::markTestSkipped('UOPZ plugin not loaded. Skip test.');
        }

        $this->prepareDatabase();

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;

        $this->getHookInstance()->start();
    }

    protected function tearDown()
    {
        $this->getHookInstance()->stop();

        global $datapoint;
        unset($datapoint);
    }

    /** TEST CASES */

    /**
     * @group hook
     */
    public function testConstructorHook()
    {
        $query = "*";
        $this->runQuery($query);

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertContains($this->host, $sqlInvocation->getEndpoint()['url']);
        self::assertEquals('root', $sqlInvocation->getEndpoint()['user']);
    }

    /**
     * @group hook
     */
    public function testQuery()
    {
        $query = "select * from pet";
        $this->runQuery($query);

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($query, $sqlInvocation->getQueries()[0]->getQuery());

        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);
        self::assertEquals('1', $sqlInvocation->getEndpoint()['hits']);
    }

    /**
     * @group hook
     */
    public function testQueryFailure()
    {
        $query = "select *";
        $this->runQuery($query);

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($query, $sqlInvocation->getQueries()[0]->getQuery());
        self::assertEquals('false', $sqlInvocation->getEndpoint()['successful']);
        self::assertArrayNotHasKey('hits', $sqlInvocation->getEndpoint());
    }

    /** END TEST CASES */


    /**
     * Initialize connection to database and test table.
     */
    abstract function prepareDatabase();

    /**
     * Returns Hook instance.
     * @return mixed
     */
    abstract function getHookInstance();

    /**
     * @param string $query
     */
    abstract function runQuery($query);
}