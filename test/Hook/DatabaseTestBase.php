<?php

namespace BitSensor\Test\Hook;

use BitSensor\Core\BitSensor;
use BitSensor\Core\Config;
use BitSensor\Test\TestBase;
use Proto\Datapoint;
use Proto\Invocation_SQLInvocation;

/**
 * Class DatabaseTestBase contains test suite setup and basic test cases.
 * @package BitSensor\Test\Hook
 */
abstract class DatabaseTestBase extends TestBase
{
    /** @var string $host */
    /** @var string $pass */
    protected $host;
    protected $pass;

    /**
     * DataBaseTestBase constructor.
     *
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->host = getenv('BITSENSOR_MYSQL_HOST') ?: 'localhost';
        $this->pass = getenv('MYSQL_ROOT_PASSWORD') ?: '';
    }

    protected function setUp()
    {
        if (!extension_loaded('uopz')) {
            self::markTestSkipped('UOPZ plugin not loaded. Skip test.');
        }

        $this->prepareDatabase();

        $config = new Config();
        $config->setUopzHook(Config::UOPZ_HOOK_OFF);

        global $bitSensor;
        $bitSensor = new BitSensor($config);
        $this->bitSensor = &$bitSensor;

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;

        $this->getHookInstance()->start();
    }

    protected function tearDown()
    {
        $this->getHookInstance()->stop();

        parent::tearDown();
    }


    /** TEST CASES */

    /**
     * @dataProvider constructorProvider
     * @param $queryFunc
     */
    public function testConstructorHook($queryFunc)
    {
        $query = "*";
        call_user_func($queryFunc, $query);

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertContains($this->host, $sqlInvocation->getEndpoint()['url']);
        self::assertEquals('root', $sqlInvocation->getEndpoint()['user']);
    }

    /**
     * @dataProvider queryFuncProvider
     * @param $queryFunc
     * @param bool $hasHits
     */
    public function testQuery($queryFunc, $hasHits = true)
    {
        $query = "select * from pet";
        call_user_func($queryFunc, $query);

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($query, $sqlInvocation->getQueries()[0]->getQuery());

        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);

        if ($hasHits)
            self::assertEquals('1', $sqlInvocation->getEndpoint()['hits']);
    }

    /**
     * @dataProvider queryFuncProvider
     * @param $queryFunc
     */
    public function testQueryFailure($queryFunc)
    {
        $query = "select *";
        call_user_func($queryFunc, $query);

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
     * Returns Query functions used for testing constructor.
     * @return array
     */
    abstract function constructorProvider();

    /**
     * Returns Query functions used for querying.
     * @return array of array of query functions and optionally $hasHit boolean
     */
    abstract function queryFuncProvider();
}