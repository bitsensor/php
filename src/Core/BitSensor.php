<?php

namespace BitSensor\Core;

use BitSensor\Connector\Connector;
use BitSensor\Handler\AfterRequestHandler;
use BitSensor\Handler\CodeErrorHandler;
use BitSensor\Handler\ExceptionHandler;
use BitSensor\Handler\Handler;
use BitSensor\Handler\HttpRequestHandler;
use BitSensor\Handler\InterfaceHandler;
use BitSensor\Handler\IpHandler;
use BitSensor\Handler\ModSecurityHandler;
use BitSensor\Handler\PluginHandler;
use BitSensor\Handler\RequestInputHandler;
use BitSensor\Hook\MysqliHook;
use BitSensor\Hook\PDOHook;
use BitSensor\Util\Log;
use Proto\Datapoint;
use Proto\GeneratedBy;
use Proto\Invocation;

/**
 * Entry point for starting the BitSensor Web Application Security plugin.
 * @package BitSensor\Core
 * @version 0.11.0
 */
class BitSensor
{

    const VERSION = '0.11.0';

    /**
     * Reference to the global Datapoint instance.
     *
     * @var Datapoint
     */
    private static $datapoint;
    /**
     * Reference to the global Configuration.
     *
     * @var Config
     */
    private static $config;

    /**
     * @var Connector to remote endpoint
     */
    private static $connector;

    /**
     * Handlers that should run on each request
     *
     * @var Handler[]
     */
    private static $handlers;

    /**
     * ErrorHandler method set by the application to be called
     *
     * @var mixed
     **/
    public static $errorHandler;

    /**
     * ExceptionHandler method set by the application to be called
     *
     * @var callable
     **/
    public static $exceptionHandler;

    /**
     * BitSensor is static.
     */
    private function __construct()
    {
    }

    static function init()
    {
        self::initializeDatapoint();

        if (!defined('BITSENSOR_WORKING_DIR')) {
            /**
             * Working directory when the application started.
             */
            define('BITSENSOR_WORKING_DIR', getcwd());
        }

        if (!isset(self::$errorHandler)) {
            self::$errorHandler = set_error_handler([CodeErrorHandler::class, 'handle']);
            Log::d("Previous error handler is: " . (is_null(self::$errorHandler) ?
                    "not defined" : (is_array(self::$errorHandler) ?
                        implode(self::$errorHandler) : self::$errorHandler)));
        }

        if (!isset(self::$exceptionHandler)) {
            self::$exceptionHandler = set_exception_handler([ExceptionHandler::class, 'handle']);
            /** @noinspection PhpParamsInspection */
            Log::d("Previous exception handler is: " . (is_null(self::$exceptionHandler) ?
                    "not defined" : (is_array(self::$exceptionHandler) ?
                        implode(self::$exceptionHandler) : self::$exceptionHandler)));
        }

        self::$handlers = [];
        self::addHandler(new PluginHandler());
        self::addHandler(new IpHandler());
        self::addHandler(new HttpRequestHandler());
        self::addHandler(new RequestInputHandler());
        self::addHandler(new ModSecurityHandler());
        self::addHandler(new InterfaceHandler());
    }

    /**
     * @param Config|string $configPath Object with configuration.
     */
    public static function configure($configPath = 'config.json')
    {
        if ($configPath instanceof Config) {
            $config = $configPath;
            Log::d('Loaded from PHP Config');
        } else {
            $config = new Config(file_get_contents($configPath));
            Log::d('Loaded from' . $configPath);
        }
        self::$config = $config;

        // Post request handling
        if (!$config->skipShutdownHandler())
            register_shutdown_function([AfterRequestHandler::class, 'handle']);

        // Load plugins
        if (self::$config->getUopzHook() === Config::UOPZ_HOOK_ON) {
            PDOHook::instance()->start();
            MysqliHook::instance()->start();
        }

        self::createConnector($config->getConnector());

        self::runHandlers();
    }

    /**
     * Adds a new entry to the context map.
     *
     * @param $key
     * @param $value
     */
    public static function putContext($key, $value)
    {
        self::$datapoint->getContext()[$key] = $value;
    }

    /**
     * Adds a new entry to the endpoint map.
     *
     * @param $key
     * @param $value
     */
    public static function putEndpoint($key, $value)
    {
        self::$datapoint->getEndpoint()[$key] = $value;
    }

    /**
     * Adds a new {@link Error} to the error collection.
     *
     * @param \Proto\Error $error
     */
    public static function addError(\Proto\Error $error)
    {
        $error->setGeneratedby(GeneratedBy::PLUGIN);
        self::$datapoint->getErrors()[] = $error;
    }

    /**
     * Adds a new entry to the input map.
     *
     * @param $key
     * @param $value
     */
    public static function putInput($key, $value)
    {
        self::$datapoint->getInput()[$key] = $value;
    }

    /**
     * The Invocation object acts as a namespace for specialized invocation objects.
     * These can be added by user-defined Handlers.
     *
     * @return Invocation object holding specialized invocation objects.
     */
    public static function getInvocations()
    {
        return self::$datapoint->getInvocation();
    }

    /**
     * Adds a new {@link Handler} that should run to collect data about the current request.
     *
     * @param Handler $handler
     */
    private static function addHandler(Handler $handler)
    {
        self::$handlers[] = $handler;
    }

    /**
     * Run all registered Handlers to collect data about the current request.
     */
    private static function runHandlers()
    {
        if (empty(self::$handlers))
            return;

        foreach (self::$handlers as $handler) {
            $handler->handle(self::$datapoint, self::$config);
        }
    }

    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * @return Datapoint
     */
    public static function getDatapoint()
    {
        return self::$datapoint;
    }

    /**
     * @return Connector
     */
    public static function getConnector()
    {
        return self::$connector;
    }

    /**
     * @param string[]|string $connectorConfiguration Can be of type string with name, or of type string[] with configuration
     */
    private static function createConnector($connectorConfiguration)
    {
        if (empty($connectorConfiguration)) {
            trigger_error("BitSensor is configured without connector. Connector configuration should be specified",
                E_USER_WARNING);
            return;
        }

        /** If configuration is set using an assoc string[] array, pass it along */
        if (is_string($connectorConfiguration)) {
            $type = $connectorConfiguration;
            $passConfiguration = false;
        } else {
            $type = $connectorConfiguration['type'];
            $passConfiguration = true;
        }

        if (strpos($type, '\\')) {
            self::$connector = new $type($passConfiguration ? $connectorConfiguration : null);
        } else {
            $bitSensorType = '\\BitSensor\\Connector\\' . ucfirst($type) . "Connector";
            self::$connector = new $bitSensorType ($passConfiguration ? $connectorConfiguration : null);
        }
    }

    private static function initializeDatapoint()
    {
        $datapoint = new Datapoint();
        $invocation = new Invocation();
        $datapoint->setInvocation($invocation);
        self::$datapoint = $datapoint;
    }

    /**
     * When the data collection phase is completed, send to remote
     *
     * @return mixed
     * @throws \BitSensor\Exception\ApiException
     */
    public static function finish()
    {
        return self::$connector->close(self::$datapoint);
    }

}

// Execute static initialization
BitSensor::init();