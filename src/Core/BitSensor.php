<?php

namespace BitSensor\Core;

use BitSensor\Blocking\Blocking;
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
 */
class BitSensor
{

    const VERSION = '1.0.5';

    /**
     * Reference to the global Datapoint instance.
     *
     * @var Datapoint
     */
    private static $datapoint;
    /**
     * @var Connector to remote endpoint
     */
    private static $connector;
    /**
     * @var Blocking $blocking
     */
    private static $blocking;
    /**
     * Handlers that should run on each request
     *
     * @var Handler[]
     */
    private static $handlers = [];
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
     * To be called on request finish if {@see setEnbaleShutdownHandler} is true
     *
     * @var AfterRequestHandler to be called on request finish
     */
    private static $afterRequestHandler;
    /**
     * Enable processing after request is finished, in general this
     * is used to signal the {@see Connector} to send the {@see Datapoint}
     *
     * @var boolean
     */
    private static $enbaleShutdownHandler = true;
    /**
     * Uopz Hooking. Turning this on enables BitSensor to hook into function calls.
     */
    private static $enbaleUopzHook = false;
    /**
     * Running mode of BitSensor pipeline. Default is {@see MODE_IDS}.
     *
     * @var string
     */
    private static $mode = self::MODE_IDS;
    /**
     * Process pipeline, false positive detection, auto-blocking
     */
    const MODE_IDS = 'ids';
    /**
     * Process pipeline, false positive detection
     */
    const MODE_MONITORING = 'monitoring';
    /**
     * Only log raw input datapoints.
     */
    const MODE_OFF = 'off';
    /**
     * Log level.
     *
     * @var string
     */
    public static $logLevel = E_ERROR;
    private static $config;

    /**
     * BitSensor is static.
     */
    private function __construct()
    {
    }

    static function init()
    {
        self::initializeDatapoint();

        self::$handlers = [];
        self::$afterRequestHandler = new AfterRequestHandler();

        self::setBlocking(new Blocking());

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
                    (is_string(self::$errorHandler[0]) ? implode(self::$errorHandler) : get_class(self::$errorHandler[0])) : self::$errorHandler)));
        }

        if (!isset(self::$exceptionHandler)) {
            self::$exceptionHandler = set_exception_handler([ExceptionHandler::class, 'handle']);
            /** @noinspection PhpParamsInspection */
            Log::d("Previous exception handler is: " . (is_null(self::$exceptionHandler) ?
                    "not defined" : (is_array(self::$exceptionHandler) ?
                    (is_string(self::$exceptionHandler[0]) ? implode(self::$exceptionHandler) : get_class(self::$exceptionHandler[0])) : self::$exceptionHandler)));
        }
    }

    /**
     * @param string|string[] $configOrPath Object with configuration.
     */
    public static function configure($configOrPath = 'config.json')
    {
        if (is_array($configOrPath)) {
            $config = $configOrPath;
        } else {
            $config = json_decode(file_get_contents($configOrPath), true);
            Log::d('BitSensor loaded configuration from location ' . $configOrPath);
        }

        self::$config = $config;

        if (array_key_exists('uopzHook', $config))
            self::setEnableUopzHook($config['uopzHook'] == 'on' ? true : false);

        if (array_key_exists('mode', $config))
            self::setMode($config['mode']);

        if (array_key_exists('logLevel', $config))
            self::setLogLevel($config['logLevel']);

        self::$afterRequestHandler->configure($config);

        if (array_key_exists('connector', $config))
            self::createConnector($config['connector']);

        if (array_key_exists('blocking', $config))
            self::createBlocking($config['blocking']);
    }

    /**
     * @throws \Exception
     */
    public static function run()
    {
        //Add handlers
        self::addHandler(PluginHandler::class);
        self::addHandler(IpHandler::class);
        self::addHandler(HttpRequestHandler::class);
        self::addHandler(RequestInputHandler::class);
        self::addHandler(ModSecurityHandler::class);
        self::addHandler(InterfaceHandler::class);

        // Post request handling
        if (self::$enbaleShutdownHandler)
            register_shutdown_function([self::$afterRequestHandler, 'handle'], self::$datapoint);

        // Load plugins
        if (self::$enbaleUopzHook) {
            PDOHook::instance()->start();
            MysqliHook::instance()->start();
        }

        self::runHandlers();

        if (isset(self::$blocking)) {
            self::getBlocking()->handle(self::$datapoint);
        }
    }

    /**
     * Run all registered Handlers to collect data about the current request.
     */
    private static function runHandlers()
    {
        if (empty(self::$handlers))
            return;

        foreach (self::$handlers as $handler) {
            $handler->handle(self::$datapoint);
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
     * @param string[]|string $connectorConfiguration Can be of type string with name,
     * or of type string[] with configuration. In the latter case, uses [type] to create
     * Connector object.
     */
    public static function createConnector($connectorConfiguration)
    {
        if (empty($connectorConfiguration))
            return;

        /** If configuration is set using an assoc string[] array, pass it along */
        if (is_string($connectorConfiguration)) {
            $type = $connectorConfiguration;
            $passConfiguration = false;
        } else {
            $type = $connectorConfiguration['type'];
            $passConfiguration = true;
        }

        if (strpos($type, '\\')) {
            self::setConnector(new $type($passConfiguration ? $connectorConfiguration : null));
        } else {
            $bitSensorType = '\\BitSensor\\Connector\\' . ucfirst($type) . "Connector";
            self::setConnector(new $bitSensorType ($passConfiguration ? $connectorConfiguration : null));
        }
    }

    /**
     * @param string[] $blockingConfiguration
     */
    public static function createBlocking($blockingConfiguration)
    {
        Blocking::configure($blockingConfiguration);
    }

    /**
     * Adds a new {@link Handler} that should run to collect data about the current request.
     *
     * @param String|Handler $handler
     * @return mixed
     */
    public static function addHandler($handler)
    {
        if (is_string($handler))
            return self::$handlers[] = new $handler(self::$config);

        return self::$handlers[] = $handler;
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
        $error->setGeneratedBy(GeneratedBy::PLUGIN);
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
     * @return Datapoint
     */
    public static function getDatapoint()
    {
        return self::$datapoint;
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
     * @return Connector
     */
    public static function getConnector()
    {
        return self::$connector;
    }

    /**
     * @return Blocking
     */
    private static function getBlocking()
    {
        return self::$blocking;
    }

    /**
     * @param Blocking $blocking
     */
    public static function setBlocking($blocking)
    {
        self::$blocking = $blocking;
    }

    /**
     * @param Connector $connector
     */
    public static function setConnector($connector)
    {
        self::$connector = $connector;
    }

    /**
     * @param bool $enbaleShutdownHandler
     */
    public static function setEnbaleShutdownHandler($enbaleShutdownHandler)
    {
        self::$enbaleShutdownHandler = $enbaleShutdownHandler;
    }

    /**
     * @return string
     */
    public static function getMode()
    {
        return self::$mode;
    }

    /**
     * @param string $mode
     */
    public static function setMode($mode)
    {
        self::$mode = $mode;
    }

    /**
     * @param mixed $enbaleUopzHook
     */
    public static function setEnableUopzHook($enbaleUopzHook)
    {
        self::$enbaleUopzHook = $enbaleUopzHook;
    }

    /**
     * @param string $logLevel
     */
    public static function setLogLevel($logLevel)
    {
        self::$logLevel = $logLevel;
    }

    /**
     * @return string Log level.
     */
    public static function getLogLevel()
    {
        return self::$logLevel;
    }

}

// Execute static initialization
BitSensor::init();
