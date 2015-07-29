<?php

namespace BITsensor\Core;


use BITsensor\Handler\AfterRequestHandler;
use BITsensor\Handler\CodeErrorHandler;
use BITsensor\Handler\ExceptionHandler;
use BITsensor\Handler\HttpRequestHandler;
use BITsensor\Handler\RequestInputHandler;

class BITsensor {

    public function __construct($uri, $apiKey) {
        define('BITSENSOR_BASE_PATH', realpath(dirname(__FILE__)) . '/');
        define('WORKING_DIR', getcwd());

        spl_autoload_register(function ($class) {
            require_once str_replace("\\", "/", $class) . '.php';
        });

        header('Content-Type: application/json'); // TODO: Debug

        global $bitSensor;
        $bitSensor = new Collector();

        set_error_handler([CodeErrorHandler::class, 'handle']);
        set_exception_handler([ExceptionHandler::class, 'handle']);
        register_shutdown_function([AfterRequestHandler::class, 'handle'], $apiKey, $bitSensor, $uri);

        HttpRequestHandler::handle($bitSensor);
        RequestInputHandler::handle($bitSensor);

        //$bitSensor->setInputProcessed(true);
        //$bitSensor->setContextProcessed(true);

        // TODO: Debug
        trigger_error("FATAL ERROR!", E_USER_ERROR);
        throw new \Exception();
    }

}