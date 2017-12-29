<?php

namespace BitSensor\Core;


use BitSensor\Exception\ApiException;
use BitSensor\Handler\AfterRequestHandler;
use BitSensor\Handler\CodeErrorHandler;
use BitSensor\Handler\ExceptionHandler;
use BitSensor\Handler\Handler;
use BitSensor\Handler\HttpRequestHandler;
use BitSensor\Handler\InterfaceHandler;
use BitSensor\Handler\IpHandler;
use BitSensor\Handler\ModSecurityHandler;
use BitSensor\Handler\RequestInputHandler;
use BitSensor\Hook\MysqliHook;
use BitSensor\Hook\PDOHook;
use BitSensor\Util\Log;
use BitSensor\View\TamperView;
use Proto\Datapoint;
use Proto\GeneratedBy;

/**
 * Entry point for starting the BitSensor Web Application Security plugin.
 * @package BitSensor\Core
 * @version 0.10.0
 */
class BitSensor
{

    const VERSION = '0.10.0';

    /**
     * User tried to tamper with the application.
     */
    const BLOCK_REASON_TAMPER = 1;
    /**
     * The API key is invalid or does not have sufficient permission to execute the action.
     */
    const BLOCK_REASON_ACCESS_DENIED = 2;
    /**
     * We don't understand what the server is saying...
     */
    const BLOCK_REASON_UNKNOWN = 3;

    /**
     * Reference to the global Datapoint instance.
     *
     * @var Datapoint
     */
    private $datapoint;
    /**
     * Reference to the global Configuration.
     *
     * @var Config
     */
    private $config;
    /**
     * Handlers that should run.
     *
     * @var Handler[]
     */
    private $handlers;

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


    public function __construct()
    {
        if (!defined('BITSENSOR_WORKING_DIR')) {
            /**
             * Working directory when the application started.
             */
            define('BITSENSOR_WORKING_DIR', getcwd());
        }

        /**
         * @global Datapoint $datapoint
         */
        global $datapoint;
        $datapoint = new Datapoint();
        $this->datapoint = &$datapoint;

        if (!isset(self::$errorHandler)) {
            self::$errorHandler = set_error_handler([CodeErrorHandler::class, 'handle']);
            Log::d("Previous error handler is: " . (is_null(self::$errorHandler) ?
                    "not defined" : (is_array(self::$errorHandler) ?
                        implode(self::$errorHandler) : self::$errorHandler)));
        }

        if (!isset(self::$exceptionHandler)) {
            self::$exceptionHandler = set_exception_handler([ExceptionHandler::class, 'handle']);
            Log::d("Previous exception handler is: " . (is_null(self::$exceptionHandler) ?
                    "not defined" : (is_array(self::$exceptionHandler) ?
                        implode(self::$exceptionHandler) : self::$exceptionHandler)));
        }

        $this->addHandler(new IpHandler());
        $this->addHandler(new HttpRequestHandler());
        $this->addHandler(new RequestInputHandler());
        $this->addHandler(new ModSecurityHandler());
        $this->addHandler(new InterfaceHandler());
    }

    /**
     * @param Config|string $configPath Object with configuration.
     */
    public function config($configPath = 'config.json')
    {
        /**
         * @global Config $config
         */
        global $config;
        if ($configPath instanceof Config) {
            $config = $configPath;
            Log::d('Loaded from PHP Config');
        } else {
            $config = new Config(file_get_contents($configPath));
            Log::d('Loaded from' . $configPath);
        }
        $this->config = &$config;

        // Post request handling
        if(!$config->skipShutdownHandler())
            register_shutdown_function([AfterRequestHandler::class, 'handle'], $this);

        if ($this->config->getMode() === Config::MODE_ON) {
            // Check if user is authorized
            try {
                $authorizationResponse = json_decode(ApiConnector::from($this->config->getUser(), $this->config->getApiKey())
                    ->to($this->config->getUri())
                    ->with($this->datapoint)
                    ->post(ApiConnector::ACTION_AUTHORIZE)
                    ->send(), true);
            } catch (ApiException $e) {
                $authorizationResponse['response'] = 'error';
            }

            if ($authorizationResponse['response'] === ApiConnector::RESPONSE_ALLOW) {
                return;
            } else if ($authorizationResponse['response'] === ApiConnector::RESPONSE_BLOCK) {
                $this->requestBlockAccess(BitSensor::BLOCK_REASON_TAMPER);
            } else if ($authorizationResponse['response'] === ApiConnector::RESPONSE_ACCESS_DENIED) {
                $this->requestBlockAccess(BitSensor::BLOCK_REASON_ACCESS_DENIED);
            } else {
                $this->requestBlockAccess(BitSensor::BLOCK_REASON_UNKNOWN);
            }
        }

        // Load plugins
        if ($this->config->getUopzHook() === Config::UOPZ_HOOK_ON) {
            PDOHook::instance()->start();
            MysqliHook::instance()->start();
        }

        $this->runHandlers();
    }

    /**
     * Request that the connecting user should be blocked. If the user will actually be blocked depends on the configuration.
     *
     * @param $reason int Reason to request a block. One of:
     * {@link BitSensor::BLOCK_REASON_TAMPER},
     * {@link BitSensor::BLOCK_REASON_ACCESS_DENIED},
     * {@link BitSensor::BLOCK_REASON_UNKNOWN}
     */
    public function requestBlockAccess($reason)
    {
        switch ($reason) {
            case BitSensor::BLOCK_REASON_TAMPER:
                if ($this->config->getMode() === Config::MODE_ON) {
                    sleep(20);
                    $view = new TamperView();
                    $view->show();
                    exit;
                }
                break;
            /** @noinspection PhpMissingBreakStatementInspection */
            case BitSensor::BLOCK_REASON_ACCESS_DENIED:
                error_log('The API key you provided does not appear to be valid. Please check your settings.');
                error_log('WARNING! BitSensor is not active!');
            case BitSensor::BLOCK_REASON_UNKNOWN:
                if ($this->config->getMode() === Config::MODE_ON && $this->config->getConnectionFail() === Config::ACTION_BLOCK) {
                    exit;
                }
                break;
        }
    }

    /**
     * Adds a new entry to the context map.
     *
     * @param $key
     * @param $value
     */
    public function putContext($key, $value)
    {
        $this->datapoint->getContext()[$key] = $value;
    }

    /**
     * Adds a new entry to the endpoint map.
     *
     * @param $key
     * @param $value
     */
    public function putEndpoint($key, $value)
    {
        $this->datapoint->getEndpoint()[$key] = $value;
    }

    /**
     * Adds a new {@link Error} to the error collection.
     *
     * @param \Proto\Error $error
     */
    public function addError(\Proto\Error $error)
    {
        $error->setGeneratedby(GeneratedBy::PLUGIN);
        $this->datapoint->getErrors()[] = $error;
    }

    /**
     * Adds a new entry to the input map.
     *
     * @param $key
     * @param $value
     */
    public function putInput($key, $value)
    {
        $this->datapoint->getInput()[$key] = $value;
    }

    /**
     * Adds a new {@link Handler} that should run to collect data about the current request.
     *
     * @param Handler $handler
     */
    private function addHandler(Handler $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * Run all registered Handlers to collect data about the current request.
     */
    private function runHandlers()
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($this->datapoint, $this->config);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Datapoint
     */
    public function getDatapoint()
    {
        return $this->datapoint;
    }
}
