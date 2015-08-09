<?php

namespace BitSensor\Core;


use BitSensor\Handler\HttpRequestHandler;
use BitSensor\Handler\RequestInputHandler;
use BitSensor\View\TamperView;

class BitSensor {

    const BLOCK_REASON_TAMPER = 1;
    const BLOCK_REASON_ACCESS_DENIED = 2;
    const BLOCK_REASON_UNKNOWN = 3;

    public function __construct($uri, $user, $apiKey) {
        define('BITSENSOR_BASE_PATH', realpath(__DIR__) . '/');
        define('WORKING_DIR', getcwd());

        global $bitSensor;
        $bitSensor = new Collector();

        set_error_handler('BitSensor\Handler\CodeErrorHandler::handle');
        set_exception_handler('BitSensor\Handler\ExceptionHandler::handle');
        register_shutdown_function('BitSensor\Handler\AfterRequestHandler::handle', $user, $apiKey, $bitSensor, $uri);

        HttpRequestHandler::handle($bitSensor);
        RequestInputHandler::handle($bitSensor);

        //$bitSensor->setInputProcessed(true);
        //$bitSensor->setContextProcessed(true);

        $authorizationResponse = json_decode(ApiConnector::from($user, $apiKey)
            ->to($uri)
            ->with($bitSensor->toArray())
            ->post(ApiConnector::ACTION_AUTHORIZE)
            ->send(), true);

        if ($authorizationResponse['response'] === ApiConnector::RESPONSE_ALLOW) {

        } else if ($authorizationResponse['response'] === ApiConnector::RESPONSE_BLOCK) {
            $this->blockAccess(BitSensor::BLOCK_REASON_TAMPER);
        } else if ($authorizationResponse['response'] === ApiConnector::RESPONSE_ACCESS_DENIED) {
            $this->blockAccess(BitSensor::BLOCK_REASON_ACCESS_DENIED);
        } else {
            $this->blockAccess(BitSensor::BLOCK_REASON_UNKNOWN);
        }
    }

    public function blockAccess($reason) {
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