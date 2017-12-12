<?php

namespace BitSensor\Hook;

use BitSensor\Core\Singleton;
use PDO;
use PDOStatement;
use Proto\Datapoint;
use Proto\Invocation;
use Proto\Invocation_SQLInvocation;
use Proto\Invocation_SQLInvocation_Query;

/**
 * Class PDOHook.
 *
 * @author Khanh Nguyen
 * @package BitSensor\Hook
 */
class PDOHook extends Singleton
{
    const EXEC = 'exec';
    const QUERY = 'query';
    const PREPARE = 'prepare';

    private $connection;
    private $username;
    public $prepareStmts = array();

    /**
     * Starts PDO execution hooks.
     */
    public function start()
    {
        if (!extension_loaded('uopz'))
            return;

        $this->stop();

        /** Hook PDO::__construct for connection information */
        uopz_set_hook(PDO::class, '__construct', function ($dsn, $username) {
            /** @var PDO $this */
            PDOHook::instance()->hookConstruct($dsn, $username);
        });

        /** Hook PDO::exec */
        uopz_set_return(PDO::class, self::EXEC, function (...$args) {
            /** @var PDO $this */
            return PDOHook::instance()->hookQuery($this, PDOHook::EXEC, $args);
        }, true);

        /** Hook PDO::query */
        uopz_set_return(PDO::class, self::QUERY, function (...$args) {
            /** @var PDO $this */
            return PDOHook::instance()->hookQuery($this, PDOHook::QUERY, $args);
        }, true);

        /** Hook PDO::prepare */
        uopz_set_hook(PDO::class, self::PREPARE, function ($statement) {
            /** @var Datapoint $datapoint */
            /** @var PDO $pdo */

            global $datapoint;
            if ($datapoint->getInvocation() == null) {
                $invocation = new Invocation();
                $datapoint->setInvocation($invocation);
            }

            $sqlInvocations = $datapoint->getInvocation()->getSQLInvocations();
            $sqlInvocation = Util::array_find($sqlInvocations,
                function (Invocation_SQLInvocation $i) use ($statement) {
                    return $i->getPrepareStatement() == $statement;
                }
            );

            // Skip execution if SQLInvocation for this statement is already created
            if ($sqlInvocation !== null)
                return;

            $sqlInvocation = new Invocation_SQLInvocation();
            $sqlInvocation->setPrepareStatement($statement);
            $sqlInvocations[] = $sqlInvocation;

            $pdo = $this;
            PDOHook::instance()->preHandle($pdo, $sqlInvocation);
        });

        /** Hook PDOStatement::bindParam */
        uopz_set_return(PDOStatement::class, 'bindParam', function ($parameter, &$variable, ...$args) {
            /** @var PDOStatement $this */
            PDOHook::instance()->prepareStmts[$this->queryString][$parameter] = &$variable;
            Util::array_unshift_ref($args, $variable);
            array_unshift($args, $parameter);

            return call_user_func_array(array($this, 'bindParam'), $args);
        }, true);

        /** Hook PDOStatement::bindValue */
        uopz_set_return(PDOStatement::class, 'bindValue', function ($parameter, $variable, ...$args) {
            /** @var PDOStatement $this */
            PDOHook::instance()->prepareStmts[$this->queryString][$parameter] = $variable;
            array_unshift($args, $variable);
            array_unshift($args, $parameter);

            return call_user_func_array(array($this, 'bindValue'), $args);
        }, true);

        /** Hook PDOStatement::execute */
        uopz_set_return(PDOStatement::class, 'execute', function (...$args) {
            /** @var PDOStatement $this */
            return PDOHook::instance()->hookStatementExecute($this, $args);
        }, true);
    }

    /**
     * Removes all PDO, PDOStatement execution hooks.
     */
    public function stop()
    {
        if (!extension_loaded('uopz'))
            return;

        uopz_unset_hook(PDO::class, '__construct');
        uopz_unset_return(PDO::class, self::EXEC);
        uopz_unset_return(PDO::class, self::QUERY);
        uopz_unset_hook(PDO::class, self::PREPARE);
        uopz_unset_return(PDOStatement::class, 'bindParam');
        uopz_unset_return(PDOStatement::class, 'bindValue');
        uopz_unset_return(PDOStatement::class, 'execute');
    }


    /**
     * @param string $connection
     * @param string $username [optional]
     */
    public function hookConstruct($connection, $username)
    {
        $this->connection = $connection;
        $this->username = $username;
    }

    /**
     * @param PDO $pdo
     * @param string $funcName
     * @param array $args
     *
     * @return PDOStatement
     */
    public function hookQuery($pdo, $funcName, $args)
    {
        /** @var Datapoint $datapoint */

        if (empty($args))
            return call_user_func_array(array($pdo, $funcName), $args);

        // Pre-handle
        $sqlInvocation = new Invocation_SQLInvocation();
        $this->preHandle($pdo, $sqlInvocation);

        $sqlQuery = new Invocation_SQLInvocation_Query();
        $sqlQuery->setQuery($args[0]);
        $sqlInvocation->getQueries()[] = $sqlQuery;

        // Execute
        $result = call_user_func_array(array($pdo, $funcName), $args);

        // Post-handle
        $this->postHandle($result, $sqlInvocation);

        // Add datapoint
        global $datapoint;
        if ($datapoint->getInvocation() == null) {
            $invocation = new Invocation();
            $datapoint->setInvocation($invocation);
        }

        $datapoint->getInvocation()->getSqlInvocations()[] = $sqlInvocation;

        return $result;
    }


    /**
     * @param PDOStatement $stmt
     * @param array $args
     *
     * @return mixed
     */
    public function hookStatementExecute($stmt, $args)
    {
        /** @var Datapoint $datapoint */
        /** @var Invocation_SQLInvocation $sqlInvocation */
        /** @var PDOStatement $result */

        $queryString = $stmt->queryString;

        // Finds current sqlInvocation for this execution.
        global $datapoint;
        $sqlInvocation = Util::array_find($datapoint->getInvocation()->getSQLInvocations(),
            function (Invocation_SQLInvocation $i) use ($queryString) {
                return $i->getPrepareStatement() == $queryString;
            }
        );

        // Skips hooking if none was found
        if ($sqlInvocation == null)
            return call_user_func_array(array($stmt, 'execute'), $args);

        $sqlQuery = new Invocation_SQLInvocation_Query();
        $sqlQuery->setQuery($queryString);

        // Adds sub-queries
        if (array_key_exists($queryString, PDOHook::instance()->prepareStmts)) {
            foreach (PDOHook::instance()->prepareStmts[$queryString] as $key => &$param) {
                $sqlQuery->getParameter()[$key] = (string)$param;
            }
            $sqlInvocation->getQueries()[] = $sqlQuery;
        }

        // Execute
        $result = call_user_func_array(array($stmt, 'execute'), $args);

        // Post handle
        $this->postHandle($result, $sqlInvocation);

        return $result;
    }

    /**
     * Pre-handling PDO execution.
     *
     * @param PDO $pdo
     * @param Invocation_SQLInvocation $sqlInvocation
     */
    public function preHandle($pdo, $sqlInvocation)
    {
        preg_match('/dbname=(.*?);/', $this->connection, $dbMatch);

        $endpoint = array(
            'url' => $this->connection,
            'catalog' => $dbMatch[1],
            'user' => $this->username,
            'server_version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
            'driver_version' => $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION),
            'database_type' => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
            'start_time' => round(microtime(true) * 1000),
            'successful' => 'false'
        );

        foreach ($endpoint as $k => $v) {
            $sqlInvocation->getEndpoint()[$k] = $v;
        }
    }

    /**
     * Post-handling PDO execution.
     *
     * @param PDOStatement $result
     * @param Invocation_SQLInvocation $sqlInvocation
     */
    public function postHandle($result, $sqlInvocation)
    {
        $sqlInvocation->getEndpoint()['execution_time'] = round(microtime(true) * 1000) - (float)$sqlInvocation->getEndpoint()['start_time'];

        if ($result !== null && $result !== false) {
            $sqlInvocation->getEndpoint()['successful'] = 'true';

            if (is_a($result, PDOStatement::class))
                $sqlInvocation->getEndpoint()['hits'] = $result->rowCount();
        }
    }
}