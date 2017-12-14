<?php

namespace BitSensor\Test\Hook;

use BitSensor\Hook\PDOHook;
use PDO;
use Proto\Invocation_SQLInvocation;
use SuperClosure\SerializableClosure;

/**
 * @author Khanh Nguyen
 * @runTestsInSeparateProcesses
 */
class PDOHookTest extends DatabaseTestBase
{
    /**
     * @inheritdoc
     */
    function prepareDatabase()
    {
        $dsn = 'mysql:host=' . $this->host . ';charset=utf8;port=3306';
        $pdo = new PDO($dsn, 'root', $this->pass);

        $pdo->exec("DROP DATABASE unittest");
        $pdo->exec("CREATE DATABASE unittest");
        $pdo->exec("USE unittest");
        $pdo->exec("CREATE TABLE pet (name VARCHAR(20), owner VARCHAR(20), species VARCHAR(20), sex CHAR(1), birth DATE, death DATE);");
        $pdo->exec("INSERT INTO pet VALUES ('Puffball','Diane','hamster','f','1999-03-30',NULL);");
        $pdo = null;
    }

    /**
     * @inheritdoc
     */
    function getHookInstance()
    {
        return PDOHook::instance();
    }

    /**
     * @inheritdoc
     */
    function queryFuncProvider()
    {
        return [
            [new SerializableClosure(function ($query) {
                $dsn = 'mysql:host=' . $this->host . ';dbname=unittest;charset=utf8;port=3306';
                $pdo = new PDO($dsn, 'root', $this->pass);
                $pdo->query($query);
            })]
        ];
    }

    /** TEST CASES  */

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
