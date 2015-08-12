<?php

namespace BitSensor\Core;


use BitSensor\Exception\ApiException;
use BitSensor\Handler\HttpRequestHandler;
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
     * @param string $uri The BitSensor server to connect to.
     * @param string $user Your BitSensor username.
     * @param string $apiKey Your BitSensor API key.
     * @throws ApiException
     */
    public function __construct($uri, $user, $apiKey) {
        /**
         * Working directory when the application started.
         */
        define('WORKING_DIR', getcwd());

        /**
         * @global Collector $bitSensor
         */
        global $bitSensor;
        $bitSensor = new Collector();

        set_error_handler('BitSensor\Handler\CodeErrorHandler::handle');
        set_exception_handler('BitSensor\Handler\ExceptionHandler::handle');
        register_shutdown_function('BitSensor\Handler\AfterRequestHandler::handle', $user, $apiKey, $bitSensor, $uri);

        HttpRequestHandler::handle($bitSensor);
        RequestInputHandler::handle($bitSensor);

        // Check if user is authorized
        $authorizationResponse = json_decode(ApiConnector::from($user, $apiKey)
            ->to($uri)
            ->with($bitSensor->toArray())
            ->post(ApiConnector::ACTION_AUTHORIZE)
            ->send(), true);

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
                sleep(20);
                $view = new TamperView();
                $view->show();
                exit;
            case BitSensor::BLOCK_REASON_ACCESS_DENIED:
                error_log('The API key you provided does not appear to be valid. Please check your settings.');
                error_log('WARNING! BitSensor is not active!');
                break;
            case BitSensor::BLOCK_REASON_UNKNOWN:
                // TODO Handle unknown response
                break;
        }
    }

}