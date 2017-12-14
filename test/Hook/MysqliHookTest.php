<?php

namespace BitSensor\Test\Hook;

use BitSensor\Hook\MysqliHook;
use Proto\Datapoint;
use Proto\Invocation_SQLInvocation;

class MysqliHookTest extends \PHPUnit_Framework_TestCase
{

    /** @var Datapoint $datapoint */
    private $datapoint;
    private $host;
    private $pass;

    /**
     * PDOHookTest constructor.
     */
    public function __construct()
    {
        $this->host = getenv('BITSENSOR_MYSQL_HOST') ?: 'localhost';
        $this->pass = getenv('MYSQL_ROOT_PASSWORD') ?: '';
    }

    protected function setUp()
    {
        if (!extension_loaded('uopz')) {
            self::markTestSkipped('UOPZ plugin not loaded. Skip test.');
        }

        $conn = mysqli_connect($this->host, 'root', $this->pass, null, 3306);

        mysqli_query($conn, "DROP DATABASE unittest");
        mysqli_query($conn, "CREATE DATABASE unittest");
        mysqli_query($conn, "USE unittest");
        $conn->query("CREATE TABLE pet (name VARCHAR(20), owner VARCHAR(20), species VARCHAR(20), sex CHAR(1), birth DATE, death DATE)");
        $conn->query("INSERT INTO pet VALUES ('Puffball','Diane','hamster','f','1999-03-30',NULL)");

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;

        MysqliHook::instance()->start();
    }

    protected function tearDown()
    {
        MysqliHook::instance()->stop();

        global $datapoint;
        unset($datapoint);
    }

    /**
     * @group hook
     */
    public function testConstructorHook()
    {
        $username = 'root';
        $conn = mysqli_connect($this->host, $username, $this->pass, 'unittest', 3306);

        $conn->query('*');

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($this->host, $sqlInvocation->getEndpoint()['url']);
        self::assertEquals($username, $sqlInvocation->getEndpoint()['user']);

    }

}