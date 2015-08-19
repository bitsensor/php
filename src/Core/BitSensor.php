<?php

namespace BitSensor\Core;


use BitSensor\Exception\ApiException;
use BitSensor\Handler\HttpRequestHandler;
use BitSensor\Handler\IpHandler;
use BitSensor\Handler\ModSecurityHandler;
use BitSensor\Handler\RequestInputHandler;
use BitSensor\View\TamperView;

/**
 * Entry point for starting the BitSensor Web Application Security plugin.
 * @package BitSensor\Core
 */
class BitSensor {

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
    private $config;

    /**
     * @param Config $config Object with configuration.
     * @throws ApiException
     */
    public function __construct($config) {
        /**
         * Working directory when the application started.
         */
        define('WORKING_DIR', getcwd());

        $this->config = $config;

        /**
         * @global Collector $collector
         */
        global $collector;
        $collector = new Collector();
        $this->collector = &$collector;

        set_error_handler('BitSensor\Handler\CodeErrorHandler::handle');
        set_exception_handler('BitSensor\Handler\ExceptionHandler::handle');
        register_shutdown_function('BitSensor\Handler\AfterRequestHandler::handle', $this->config->getUser(), $this->config->getApiKey(), $collector, $this->config->getUri());

        IpHandler::handle($collector, $config);
        HttpRequestHandler::handle($collector);
        RequestInputHandler::handle($collector);
        ModSecurityHandler::handle($collector);

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
                error_log('WARNING! BITsensor is not active!');
            case BitSensor::BLOCK_REASON_UNKNOWN:
                if ($this->config->getMode() === Config::MODE_ON && $this->config->getConnectionFail() === Config::ACTION_BLOCK) {
                    exit;
                }
                break;
        }
    }

    public function addContext(Context $context) {
        $this->collector->addContext($context);
    }

    public function addEndpointContext(Context $context) {
        $this->collector->addEndpointContext($context);
    }

    public function addError(Error $error) {
        $this->collector->addError($error);
    }

    public function addInput(Context $input) {
        $this->collector->addInput($input);
    }

}