<?php

namespace BitSensor\Test\Hook;

use BitSensor\Hook\PDOHook;
use PDO;
use Proto\Datapoint;
use Proto\Invocation_SQLInvocation;

class PDOHookTest extends \PHPUnit_Framework_TestCase
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


        $dsn = 'mysql:host=' . $this->host . ';charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);

        $pdo->exec("DROP DATABASE unittest");
        $pdo->exec("CREATE DATABASE unittest");
        $pdo->exec("USE unittest");
        $pdo->exec("CREATE TABLE pet (name VARCHAR(20), owner VARCHAR(20), species VARCHAR(20), sex CHAR(1), birth DATE, death DATE);");
        $pdo->exec("INSERT INTO pet VALUES ('Puffball','Diane','hamster','f','1999-03-30',NULL);");
        $pdo = null;

        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;

        PDOHook::instance()->start();
    }

    protected function tearDown()
    {
        PDOHook::instance()->stop();

        global $datapoint;
        unset($datapoint);
    }

    /**
     * @group hook
     */
    public function testConstructorHook()
    {
        $username = 'root';
        $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
        $pdo = new PDO($dsn, $username, $this->pass);

        $pdo->query('*');

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($dsn, $sqlInvocation->getEndpoint()['url']);
        self::assertEquals($username, $sqlInvocation->getEndpoint()['user']);

    }

    /**
     * @group hook
     */
    public function testQuery()
    {
        $query = "select * from pet";

        $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);
        $pdo->query($query);

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

        $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);
        $pdo->query($query);

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($query, $sqlInvocation->getQueries()[0]->getQuery());
        self::assertEquals('false', $sqlInvocation->getEndpoint()['successful']);
        self::assertArrayNotHasKey('hits', $sqlInvocation->getEndpoint());
    }

    /**
     * @group hook
     */
    public function testPrepareBindParam()
    {
        $prepare = "INSERT INTO pet VALUES ('Puffball',:owner,'hamster','f','1999-03-30',NULL)";

        $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);
        $stmt = $pdo->prepare($prepare);
        $stmt->bindParam(':owner', $owner);

        $owner = 'Harry';
        $stmt->execute();

        $owner = 'Miley';
        $stmt->execute();

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($prepare, $sqlInvocation->getPrepareStatement());
        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);
        self::assertArrayNotHasKey('hits', $sqlInvocation->getEndpoint());

        self::assertEquals(2, count($sqlInvocation->getQueries()));
        self::assertEquals($owner, $sqlInvocation->getQueries()[1]->getParameter()[':owner']);
    }

    /**
     * @group hook
     */
    public function testPrepareBindValue()
    {
        $prepare = "INSERT INTO pet VALUES ('Puffball',:owner,'hamster','f','1999-03-30',NULL)";

        $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);
        $stmt = $pdo->prepare($prepare);

        $stmt->bindValue(':owner', 'Harry');
        $stmt->execute();

        $stmt->bindValue(':owner', 'Miley');
        $stmt->execute();

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        self::assertEquals($prepare, $sqlInvocation->getPrepareStatement());
        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);
        self::assertArrayNotHasKey('hits', $sqlInvocation->getEndpoint());

        self::assertEquals(2, count($sqlInvocation->getQueries()));
        self::assertEquals('Miley', $sqlInvocation->getQueries()[1]->getParameter()[':owner']);
    }

    /**
     * @group hook
     */
    public function testPrepareExecute()
    {
        $query = "SELECT name, owner FROM pet";

        $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        /** @var Invocation_SQLInvocation $sqlInvocation */
        $sqlInvocation = $this->datapoint->getInvocation()->getSQLInvocations()[0];

        $result = $stmt->fetchAll();

        self::assertEquals('Puffball', $result[0][0]);
        self::assertEquals($query, $sqlInvocation->getPrepareStatement());
        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);
    }
}
