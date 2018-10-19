<?php

namespace BitSensor\Test\Hook;

use BitSensor\Core\BitSensor;
use BitSensor\Hook\MysqliHook;
use mysqli;
use mysqli_stmt;
use Proto\Invocation\SQLInvocation;
use SuperClosure\SerializableClosure;

/**
 * @author Khanh Nguyen
 * @runTestsInSeparateProcesses
 */
class MysqliHookTest extends DatabaseTestBase
{

    /**
     * @inheritdoc
     */
    function prepareDatabase()
    {
        $conn = mysqli_connect($this->host, 'root', $this->pass, null, 3306);

        mysqli_query($conn, "DROP DATABASE unittest");
        mysqli_query($conn, "CREATE DATABASE unittest");
        mysqli_query($conn, "USE unittest");
        $conn->query("CREATE TABLE pet (name VARCHAR(20), owner VARCHAR(20), species VARCHAR(20), sex CHAR(1), birth DATE, death DATE)");
        $conn->query("INSERT INTO pet VALUES ('Puffball','Diane','hamster','f','1999-03-30',NULL)");
    }

    /**
     * @inheritdoc
     */
    function getHookInstance()
    {
        return MysqliHook::instance();
    }

    /**
     * @inheritdoc
     */
    function constructorProvider()
    {
        /**
         * mysqli->__construct ($host, $username, $passwd, $dbname, $port, $socket)
         * mysqli->connect ($host, $user, $password, $database, $port, $socket)
         * mysqli->real_connect ($host = null, $username = null, $passwd = null, $dbname = null, $port = null, $socket = null, $flags = null)
         * mysqli_connect ($host = '', $user = '', $password = '', $database = '', $port = '', $socket = '')
         * mysqli_real_connect ($link, $host = '', $user = '', $password = '', $database = '', $port = '', $socket = '', $flags = null)
         */
        return [
            "mysqli->__construct" => [new SerializableClosure(function ($query) {
                $conn = new mysqli($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->query($query);
            })],
            "mysqli->connect" => [new SerializableClosure(function ($query) {
                $conn = new mysqli();
                $conn->connect($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->query($query);
            })],
            "mysqli->real_connect" => [new SerializableClosure(function ($query) {
                $conn = new mysqli();
                $conn->real_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->query($query);
            })],
            "mysqli_connect" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->query($query);
            })],
            "mysqli_real_connect" => [new SerializableClosure(function ($query) {
                $conn = new mysqli();
                mysqli_real_connect($conn, $this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->query($query);
            })]
        ];
    }

    /**
     * @inheritdoc
     */
    function queryFuncProvider()
    {
        /**
         * mysqli->multi_query ($query) : bool
         * mysqli->query ($query, $resultmode = MYSQLI_STORE_RESULT)
         * mysqli->real_query ($query) : bool
         * mysqli_multi_query ($link, $query) : bool
         * mysqli_query ($link, $query, $resultmode = MYSQLI_STORE_RESULT)
         * mysqli_real_query ($link, $query) : bool
         */
        return [
            "mysqli->multi_query" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->multi_query($query);
            }), false],
            "mysqli->query" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->query($query);
            })],
            "mysqli->real_query" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                $conn->real_query($query);
            }), false],
            "mysqli_multi_query" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                mysqli_multi_query($conn, $query);
            }), false],
            "mysqli_query" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                mysqli_query($conn, $query);
            })],
            "mysqli_real_query" => [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
                mysqli_real_query($conn, $query);
            }), false],
        ];
    }

    /**
     * @dataProvider bindParamProvider
     */
    public function testPrepareBindParam($function)
    {
        $prepare = "INSERT INTO pet VALUES ('Puffball',?,'hamster','f','1999-03-30',NULL)";

        $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
        $stmt = $conn->prepare($prepare);

        $owners = ['Harry', 'Miley'];

        call_user_func($function, $stmt, $owners);

        /** @var SQLInvocation $sqlInvocation */
        $sqlInvocation = BitSensor::getInvocations()->getSQLInvocations()[0];

        self::assertEquals($prepare, $sqlInvocation->getPrepareStatement());
        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);
        self::assertArrayNotHasKey('hits', $sqlInvocation->getEndpoint());

        self::assertEquals(2, count($sqlInvocation->getQueries()));
        self::assertEquals($owners[1], $sqlInvocation->getQueries()[1]->getParameter()[0]);
    }

    /**
     * @dataProvider executeProvider
     */
    public function testPrepareExecute($function)
    {
        $query = "SELECT name, owner FROM pet";

        $conn = mysqli_connect($this->host, 'root', $this->pass, 'unittest', 3306);
        $stmt = $conn->prepare($query);
        call_user_func($function, $stmt);

        /** @var SQLInvocation $sqlInvocation */
        $sqlInvocation = BitSensor::getInvocations()->getSQLInvocations()[0];

        $stmt->bind_result($name, $owner);

        self::assertTrue($stmt->fetch());
        self::assertEquals('Puffball', $name);
        self::assertEquals($query, $sqlInvocation->getPrepareStatement());
        self::assertEquals('true', $sqlInvocation->getEndpoint()['successful']);
    }

    public function executeProvider()
    {
        return [
            "mysqli_stmt->execute" => [new SerializableClosure(function ($stmt) {
                /** @var mysqli_stmt $stmt */
                $stmt->execute();
            })],
            "mysqli_stmt_execute" => [new SerializableClosure(function ($stmt) {
                /** @var mysqli_stmt $stmt */
                mysqli_stmt_execute($stmt);
            })],
            "mysqli_execute" => [new SerializableClosure(function ($stmt) {
                /** @var mysqli_stmt $stmt */
                mysqli_execute($stmt);
            })]
        ];
    }

    public function bindParamProvider()
    {
        return [
            "mysqli_stmt->bind_param" => [new SerializableClosure(function ($stmt, $owners) {
                /** @var mysqli_stmt $stmt */
                $stmt->bind_param('s', $owner);

                foreach ($owners as $owner) {
                    $stmt->execute();
                }
            })],
            "mysqli_stmt_bind_param" => [new SerializableClosure(function ($stmt, $owners) {
                /** @var mysqli_stmt $stmt */
                mysqli_stmt_bind_param($stmt, 's', $owner);

                foreach ($owners as $owner) {
                    $stmt->execute();
                }
            })]
        ];
    }
}
