<?php

namespace BitSensor\Core;


use BitSensor\Exception\ApiException;
use BitSensor\Handler\Handler;
use BitSensor\Handler\HttpRequestHandler;
use BitSensor\Handler\InterfaceHandler;
use BitSensor\Handler\IpHandler;
use BitSensor\Handler\ModSecurityHandler;
use BitSensor\Handler\RequestInputHandler;
use BitSensor\Util\Log;
use BitSensor\View\TamperView;

/**
 * Entry point for starting the BitSensor Web Application Security plugin.
 * @package BitSensor\Core
 * @version 0.9.4
 */
class BitSensor {

    const VERSION = '0.9.5';

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
     * Reference to the global collector instance.
     *
     * @var Collector
     */
    private $collector;
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
    * @var string
    **/
    public $errorHandler;


    /**
    * ExceptionHandler method set by the application to be called
    *
    * @var string
    **/
    public $exceptionHandler;

    /**
     * @param Config|string $configPath Object with configuration.
     * @throws ApiException
     */
    public function __construct($configPath = 'config.json') {
        if (!defined('BITSENSOR_WORKING_DIR')) {
            /**
             * Working directory when the application started.
             */
            define('BITSENSOR_WORKING_DIR', getcwd());
        }

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

        /**
         * @global Collector $collector
         */
        global $collector;
        $collector = new Collector();
        $this->collector = &$collector;

        $this->errorHandler = set_error_handler('BitSensor\Handler\CodeErrorHandler::handle');
        Log::d("Previous error handler is: " . (is_null($this->errorHandler) ? "not defined" : $this->errorHandler));

        $this->exceptionHandler = set_exception_handler('BitSensor\Handler\ExceptionHandler::handle');
        Log::d("Previous exception handler is: " . (is_null($this->exceptionHandler) ? "not defined" : $this->exceptionHandler));

        register_shutdown_function('BitSensor\Handler\AfterRequestHandler::handle', $collector, $config);

        $this->addHandler(new IpHandler());
        $this->addHandler(new HttpRequestHandler());
        $this->addHandler(new RequestInputHandler());
        $this->addHandler(new ModSecurityHandler());
        $this->addHandler(new InterfaceHandler());

        $this->runHandlers();

        if ($this->config->getMode() === Config::MODE_ON) {
            // Check if user is authorized
            try {
                $authorizationResponse = json_decode(ApiConnector::from($this->config->getUser(), $this->config->getApiKey())
                    ->to($this->config->getUri())
                    ->with($collector->toArray())
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
    }

    /**
     * Request that the connecting user should be blocked. If the user will actually be blocked depends on the configuration.
     *
     * @param $reason int Reason to request a block. One of:
     * {@link BitSensor::BLOCK_REASON_TAMPER},
     * {@link BitSensor::BLOCK_REASON_ACCESS_DENIED},
     * {@link BitSensor::BLOCK_REASON_UNKNOWN}
     */
    public function requestBlockAccess($reason) {
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
     * Adds a new {@link Context} to the context collection.
     *
     * @param Context $context
     */
    public function addContext(Context $context) {
        $this->collector->addContext($context);
    }

    /**
     * Adds a new {@link Context} to the endpoint collection.
     *
     * @param Context $context
     */
    public function addEndpointContext(Context $context) {
        $this->collector->addEndpointContext($context);
    }

    /**
     * Adds a new {@link Error} to the error collection.
     *
     * @param Error $error
     */
    public function addError(Error $error) {
        $this->collector->addError($error);
    }

    /**
     * Adds a new {@link Context} to the input collection.
     *
     * @param Context $input
     */
    public function addInput(Context $input) {
        $this->collector->addInput($input);
    }

    /**
     * Adds a new {@link Handler} that should run to collect data about the current request.
     *
     * @param Handler $handler
     */
    private function addHandler(Handler $handler) {
        $this->handlers[] = $handler;
    }

    /**
     * Run all registered Handlers to collect data about the current request.
     */
    private function runHandlers() {
        foreach ($this->handlers as $handler) {
            $handler->handle($this->collector, $this->config);
        }
    }

    public function getConfig() {
        return $this->config;
    }
}
