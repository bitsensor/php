<?php
define('BITsensorBasePath', realpath(dirname(__FILE__)) . '/');

include_once 'Config.php';

include_once BITsensorBasePath . 'Core/Log/Collector.php';

include_once BITsensorBasePath . 'Core/Handler/CodeErrorHandler.php';

InitializeErrorHandler();
register_shutdown_function('InitializeShutdown');

function InitializeErrorHandler(){
    set_error_handler("CodeErrorHandler::Handle");
}

function InitializeShutdown(){
    include BITsensorBasePath . 'Core/Handler/AfterRequest.php';
}

/*@var $BITsensor Collector*/
$BITsensor = new Collector();

include BITsensorBasePath . 'Core/Handler/HttpRequestHandler.php';
include BITsensorBasePath . 'Core/Handler/InputHandler.php';

