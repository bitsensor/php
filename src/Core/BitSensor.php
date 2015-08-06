<?php

namespace BitSensor\Core;


use BitSensor\Handler\HttpRequestHandler;
use BitSensor\Handler\RequestInputHandler;

class BitSensor {

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
            echo 'Blocked';
            exit;
        } else if ($authorizationResponse['response'] === ApiConnector::RESPONSE_ACCESS_DENIED) {
            echo 'Access Denied';
            exit;
        } else {
            echo 'Unknown';
            exit;
        }
    }

}