<?php

namespace BitSensor\Test\Hook;

use BitSensor\Hook\MysqliHook;
use mysqli;
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
    function queryFuncProvider()
    {
        /**
         * mysqli->__construct ($host, $username, $passwd, $dbname, $port, $socket)
         * mysqli->connect ($host, $user, $password, $database, $port, $socket)
         * mysqli->real_connect ($host = null, $username = null, $passwd = null, $dbname = null, $port = null, $socket = null, $flags = null)
         * mysqli_connect ($host = '', $user = '', $password = '', $database = '', $port = '', $socket = '')
         * mysqli_real_connect ($link, $host = '', $user = '', $password = '', $database = '', $port = '', $socket = '', $flags = null)
         */
        return [
            [new SerializableClosure(function ($query) {
                $conn = new mysqli($this->host, 'root', $this->pass, null, 3306);
                $conn->query($query);
            })],
            [new SerializableClosure(function ($query) {
                $conn = new mysqli();
                $conn->connect($this->host, 'root', $this->pass, null, 3306);
                $conn->query($query);
            })],
            [new SerializableClosure(function ($query) {
                $conn = new mysqli();
                $conn->real_connect($this->host, 'root', $this->pass, null, 3306);
                $conn->query($query);
            })],
            [new SerializableClosure(function ($query) {
                $conn = mysqli_connect($this->host, 'root', $this->pass, null, 3306);
                $conn->query($query);
            })],
            [new SerializableClosure(function ($query) {
                $conn = new mysqli();
                mysqli_real_connect($conn, $this->host, 'root', $this->pass, null, 3306);
                $conn->query($query);
            })]
        ];
    }
}