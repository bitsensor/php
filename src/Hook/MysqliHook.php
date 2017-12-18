<?php

namespace BitSensor\Hook;

use mysqli;
use mysqli_result;
use mysqli_stmt;
use Proto\Datapoint;
use Proto\Invocation;
use Proto\Invocation_SQLInvocation;
use Proto\Invocation_SQLInvocation_Query;

/**
 * Class MysqlHook.
 *
 * @author Khanh Nguyen
 * @package BitSensor\Hook
 */
class MysqliHook extends AbstractHook
{
    /**
     * mysqli constructor functions.
     *
     * mysqli->__construct ($host, $username, $passwd, $dbname, $port, $socket)
     * mysqli->connect ($host, $user, $password, $database, $port, $socket)
     * mysqli->real_connect ($host = null, $username = null, $passwd = null, $dbname = null, $port = null, $socket = null, $flags = null)
     * mysqli_connect ($host = '', $user = '', $password = '', $database = '', $port = '', $socket = '')
     * mysqli_real_connect ($link, $host = '', $user = '', $password = '', $database = '', $port = '', $socket = '', $flags = null)
     */
    const CONSTRUCTOR_FUNCTIONS = [
        [mysqli::class, '__construct'],
        [mysqli::class, 'connect'],
        [mysqli::class, 'real_connect'],
        ['mysqli_connect'],
        ['mysqli_real_connect']
    ];

    /**
     * mysqli query functions.
     *
     * mysqli->multi_query ($query)
     * mysqli->query ($query, $resultmode = MYSQLI_STORE_RESULT)
     * mysqli->real_query ($query)
     * mysqli_multi_query ($link, $query)
     * mysqli_query ($link, $query, $resultmode = MYSQLI_STORE_RESULT)
     * mysqli_real_query ($link, $query)
     */
    const QUERY_FUNCTIONS = [
        [mysqli::class, 'multi_query'],
        [mysqli::class, 'query'],
        [mysqli::class, 'real_query'],
        ['mysqli_multi_query'],
        ['mysqli_query'],
        ['mysqli_real_query']
    ];

    /**
     * mysqli prepare functions.
     *
     * mysqli->prepare ($query)
     * mysqli_stmt->prepare ($query)
     * mysqli_prepare ($link, $query)
     * mysqli_stmt_prepare (mysqli_stmt $stmt, $query)
     */
    const PREPARE_FUNCTIONS = [
        [mysqli::class, 'prepare'],
        [mysqli_stmt::class, 'prepare'],
        ['mysqli_prepare'],
        ['mysqli_stmt_prepare']
    ];

    /**
     * mysqli_stmt bind_param functions.
     *
     * mysqli_stmt->bind_param ($types, &$var1, &$_ = null)
     * mysqli_stmt_bind_param ($stmt, $types, &...$var1)
     * mysqli_bind_param ($stmt, $types)
     */
    const BIND_PARAM_FUNCTIONS = [
        [mysqli_stmt::class, 'bind_param'],
        ['mysqli_stmt_bind_param'],
    ];

    /**
     * mysqli_stmt execute functions.
     *
     * mysqli_stmt->execute ()
     * mysqli_stmt_execute ($stmt)
     * mysqli_execute ($stmt)
     */
    const EXECUTE_FUNCTIONS = [
        [mysqli_stmt::class, 'execute'],
        ['mysqli_stmt_execute'],
        ['mysqli_execute']
    ];

    private $connection;
    private $username;
    private $database;

    /**
     * A binding map which maps a prepare statement as key to a binding as value.
     * @var array
     */
    public $bindingMap = [];

    /**
     * Starts Mysqli execution hooks.
     */
    public function init()
    {
        /**
         * Hook constructor functions.
         */
        foreach (self::CONSTRUCTOR_FUNCTIONS as $function) {
            $function[] = $this->hookConstruct();
            Util::call_ignore_exception_array('uopz_set_hook', $function);
        }

        /**
         * Hook query functions.
         */
        foreach (self::QUERY_FUNCTIONS as $function) {
            $function[] = $this->hookQuery($function);
            $function[] = true;
            Util::call_ignore_exception_array('uopz_set_return', $function);
        }

        /**
         * Hook mysqli prepare functions.
         */
        foreach (self::PREPARE_FUNCTIONS as $function) {
            $function[] = $this->hookPrepare();
            Util::call_ignore_exception_array('uopz_set_hook', $function);
        }

        /**
         * Hook mysqli_stmt bindParam functions.
         */
        foreach (self::BIND_PARAM_FUNCTIONS as $function) {
            $function[] = $this->hookBindParam($function);
            $function[] = true;
            Util::call_ignore_exception_array('uopz_set_return', $function);
        }

        /**
         * Hook mysqli_stmt execute.
         */
        foreach (self::EXECUTE_FUNCTIONS as $function) {
            $function[] = $this->hookExecute($function);
            $function[] = true;
            Util::call_ignore_exception_array('uopz_set_return', $function);
        }
    }

    /**
     * Removes all mysqli, mysqli_stmt execution hooks.
     */
    public function destroy()
    {
        $hookFunctions = array_merge(self::CONSTRUCTOR_FUNCTIONS,
            self::PREPARE_FUNCTIONS);
        $returnFunctions = array_merge(self::QUERY_FUNCTIONS,
            self::BIND_PARAM_FUNCTIONS,
            self::EXECUTE_FUNCTIONS);

        foreach ($hookFunctions as $function) {
            call_user_func_array('uopz_unset_hook', $function);
        }
        foreach ($returnFunctions as $function) {
            call_user_func_array('uopz_unset_return', $function);
        }
    }

    private function hookConstruct()
    {
        return function (...$args) {
            if (empty($args))
                return;

            if (is_a($args[0], mysqli::class))
                array_shift($args);

            $host = $args[0];
            $port = $args[4];
            $username = $args[1];
            $database = $args[3];

            $connection = 'mysql://' . $host . ($port ? ':' . $port : '') . ($database ? '/' . $database : '');

            MysqliHook::instance()->updateConnectionInfo($connection, $username, $database);
        };
    }

    private function hookQuery($function)
    {
        return function (...$args) use ($function) {
            /** @var Datapoint $datapoint */
            /** @var Invocation $invocation */

            $function = isset($function[1]) ? [$this, $function[1]] : $function[0];

            if (empty($args)) {
                call_user_func($function);
            }

            $mysqli = is_a($args[0], mysqli::class)
                ? $args[0]
                : $this;
            $query = is_a($args[0], mysqli::class)
                ? $args[1]
                : $args[0];

            // Pre-handle
            $sqlInvocation = new Invocation_SQLInvocation();
            MysqliHook::instance()->preHandle($mysqli, $sqlInvocation);

            $sqlQuery = new Invocation_SQLInvocation_Query();
            $sqlQuery->setQuery($query);
            $sqlInvocation->getQueries()[] = $sqlQuery;

            //Execute
            $result = call_user_func_array($function, $args);

            // Post-handle
            MysqliHook::instance()->postHandle($result, $sqlInvocation);

            // Add datapoint
            global $datapoint;
            if ($datapoint->getInvocation() == null) {
                $invocation = new Invocation();
                $datapoint->setInvocation($invocation);
            }

            $invocation = $datapoint->getInvocation();
            $invocation->getSqlInvocations()[] = $sqlInvocation;

            return $result;
        };
    }

    private function hookPrepare()
    {
        return function (...$args) {
            /** @var Datapoint $datapoint */

            if (empty($args))
                return;

            $mysqli = is_a($args[0], mysqli::class)
                ? $args[0]
                : $this;
            $statement = is_a($args[0], mysqli::class)
                ? $args[1]
                : $args[0];

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

            // Reset bindingMaps for this $statement if already set
            PDOHook::instance()->bindingMap[$statement] = [];

            // Pre-handle
            MysqliHook::instance()->preHandle($mysqli, $sqlInvocation);
        };
    }

    private function hookBindParam($function)
    {
        $internalHookBindParam = function ($function, $args, &$variables) {
            /** @var Datapoint $datapoint */
            /** @var Invocation_SQLInvocation $sqlInvocation */

            // Finds current sqlInvocation for this execution.
            global $datapoint;
            if ($datapoint->getInvocation() == null)
                return call_user_func_array($function, $args);

            $sqlInvocations = $datapoint->getInvocation()->getSQLInvocations();
            $sqlInvocation = $sqlInvocations[$sqlInvocations->count() - 1];

            // Skips hooking if none was found
            if ($sqlInvocation == null || empty($sqlInvocation->getPrepareStatement()))
                return call_user_func_array($function, $args);

            $queryString = $sqlInvocation->getPrepareStatement();

            // Reset prepareStmts after put all bindings to query.
            MysqliHook::instance()->bindingMap = [];
            MysqliHook::instance()->bindingMap[$queryString] = &$variables;

            return call_user_func_array($function, $args);
        };

        // Note: This is a workaround. These two functions can not be merged because the parameters they accepts are different.
        if (isset($function[1]))
            return function ($types, &...$variables) use ($function, $internalHookBindParam) {
                $function = [$this, $function[1]];
                $args = [$types];
                $args = array_merge($args, $variables);

                return $internalHookBindParam($function, $args, $variables);
            };
        else
            return function ($stmt, $types, &...$variables) use ($function, $internalHookBindParam) {
                $function = $function[0];
                $args = [$stmt, $types];
                $args = array_merge($args, $variables);

                return $internalHookBindParam($function, $args, $variables);
            };
    }

    private function hookExecute($function)
    {
        return function (...$args) use ($function) {
            /** @var Datapoint $datapoint */
            /** @var Invocation_SQLInvocation $sqlInvocation */
            /** @var mysqli_result $result */

            $function = isset($function[1]) ? [$this, $function[1]] : $function[0];

            if (empty($args)) {
                call_user_func($function);
            }

            // Finds current sqlInvocation for this execution.
            global $datapoint;
            if ($datapoint->getInvocation() == null)
                return call_user_func_array($function, $args);

            $sqlInvocations = $datapoint->getInvocation()->getSQLInvocations();
            $sqlInvocation = $sqlInvocations[$sqlInvocations->count() - 1];

            // Skips hooking if none was found
            if ($sqlInvocation == null || empty($sqlInvocation->getPrepareStatement()))
                return call_user_func_array($function, $args);

            $queryString = $sqlInvocation->getPrepareStatement();
            $sqlQuery = new Invocation_SQLInvocation_Query();
            $sqlQuery->setQuery($queryString);

            // Adds sub-queries
            if (array_key_exists($queryString, MysqliHook::instance()->bindingMap)) {
                foreach (MysqliHook::instance()->bindingMap[$queryString] as $key => &$param) {
                    $sqlQuery->getParameter()[$key] = (string)$param;
                }
                $sqlInvocation->getQueries()[] = $sqlQuery;
            }

            // Execute
            $result = call_user_func_array($function, $args);

            // Post handle
            MysqliHook::instance()->postHandle($result, $sqlInvocation);

            return $result;
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

    /**
     * Pre-handling Mysqli execution.
     *
     * @param mysqli $mysqli
     * @param Invocation_SQLInvocation $sqlInvocation
     */
    public function preHandle($mysqli, $sqlInvocation)
    {
        $endpoint = array(
            'url' => $this->connection,
            'user' => $this->username,
            'catalog' => $this->database,
            'server_version' => $mysqli->server_info,
            'driver_version' => $mysqli->client_info,
            'database_type' => 'mysql',
            'start_time' => round(microtime(true) * 1000),
            'successful' => 'false'
        );

        foreach ($endpoint as $k => $v) {
            $sqlInvocation->getEndpoint()[$k] = $v;
        }
    }

    /**
     * Post-handling Mysqli execution.
     *
     * @param mixed $result
     * @param Invocation_SQLInvocation $sqlInvocation
     */
    public function postHandle($result, $sqlInvocation)
    {
        $sqlInvocation->getEndpoint()['execution_time'] = round(microtime(true) * 1000) - (float)$sqlInvocation->getEndpoint()['start_time'];

        if ($result !== null && $result !== false) {
            $sqlInvocation->getEndpoint()['successful'] = 'true';

            if (is_a($result, mysqli_result::class))
                $sqlInvocation->getEndpoint()['hits'] = $result->num_rows;
        }
    }
}