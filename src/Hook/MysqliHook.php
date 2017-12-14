<?php

namespace BitSensor\Hook;

use BitSensor\Core\Singleton;
use mysqli;

/**
 * Class MysqlHook.
 *
 * @author Khanh Nguyen
 * @package BitSensor\Hook
 */
class MysqliHook extends Singleton
{
    const VERSION_REQUIREMENT = "5.0.0";
    const QUERY = 'query';
    const PREPARE = 'prepare';

    private $connection;
    private $username;
    private $database;
    public $prepareStmts = array();

    private $started = false;

    /**
     * Starts PDO execution hooks.
     */
    public function start()
    {
        if (!extension_loaded('uopz') || $this->started)
            return;

        if (version_compare(phpversion('uopz'), self::VERSION_REQUIREMENT) < 0)
            trigger_error("PDOHook not starting with 'uopz' version (" . phpversion('uopz') . ") lower than " . self::VERSION_REQUIREMENT,
                E_USER_WARNING);

        $this->started = true;

        /**
         * Hook mysqli constructor
         *
         * mysqli->__construct ($host, $username, $passwd, $dbname, $port, $socket)
         * mysqli->connect ($host, $user, $password, $database, $port, $socket)
         * mysqli->real_connect ($host = null, $username = null, $passwd = null, $dbname = null, $port = null, $socket = null, $flags = null)
         * mysqli->mysqli ($host, $user, $password, $database, $port, $socket)
         * mysqli_connect ($host = '', $user = '', $password = '', $database = '', $port = '', $socket = '')
         * mysqli_real_connect ($link, $host = '', $user = '', $password = '', $database = '', $port = '', $socket = '', $flags = null)
         */
        uopz_set_hook(mysqli::class, '__construct', self::hookConstruct());
        uopz_set_hook(mysqli::class, 'connect', self::hookConstruct());
        uopz_set_hook('mysqli_connect', self::hookConstruct());

        /**
         * Hook mysqli query
         *
         * mysqli->multi_query ($query)
         * mysqli->query ($query, $resultmode = MYSQLI_STORE_RESULT)
         * mysqli->real_query ($query)
         * mysqli_multi_query ($link, $query)
         * mysqli_query ($link, $query, $resultmode = MYSQLI_STORE_RESULT)
         * mysqli_real_query ($link, $query)
         */
        uopz_set_return(mysqli::class, self::QUERY, $this->hookQuery(), true);
        uopz_set_return('mysqli_query', $this->hookQuery(), true);

        /**
         * Hook mysqli prepare
         *
         * mysqli->prepare ($query)
         * mysqli_stmt->prepare ($query)
         * mysqli_prepare ($link, $query)
         * mysqli_stmt_prepare (mysqli_stmt $stmt, $query)
         */
        uopz_set_hook(mysqli::class, self::PREPARE, $this->hookPrepare());
        uopz_set_hook('mysqli_prepare', $this->hookPrepare());


        /**
         * Hook mysqli_stmt execute.
         *
         * mysqli_stmt->execute ()
         * mysqli_stmt_execute ($stmt)
         * mysqli_execute ($stmt)
         */

    }

    /**
     * Removes all PDO, PDOStatement execution hooks.
     */
    public function stop()
    {
        if (!$this->started)
            return;

        $this->started = false;

        uopz_unset_hook(mysqli::class, '__construct');
        uopz_unset_hook(mysqli::class, 'connect');
        uopz_unset_hook('mysqli_connect');

        uopz_unset_return(mysqli::class, self::QUERY);
    }

    public function hookConstruct()
    {
        return function (...$args) {
            if (empty($args))
                return;

            $host = $args[0];
            $port = $args[4];
            $username = $args[1];
            $database = $args[3];

            $connection = 'mysql://' . $host . ($port ? ':' . $port : '') . ($database ? '/' . $database : '');

            MysqliHook::instance()->updateConnectionInfo($connection, $username, $database);
        };
    }

    public function hookQuery()
    {
        return function (...$args) {
            /** @var mysqli $this */
            throw new \Error('not implemented');
        };
    }


    public function hookPrepare()
    {
        return function (...$args) {

        };
    }


    /**
     * @param string $connection
     * @param string $username
     * @param string $database
     */
    public function updateConnectionInfo($connection, $username, $database)
    {
        $this->connection = $connection;
        $this->username = $username;
        $this->database = $database;
    }
}