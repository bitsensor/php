<?php
define('BITsensorBasePath', realpath(dirname(__FILE__)) . '/');

include_once 'Config.php';

include_once BITsensorBasePath . 'Core/Log/Collector.php';

include_once BITsensorBasePath . 'Core/Handler/CodeErrorHandler.php';

set_error_handler("CodeErrorHandler::Handle");
register_shutdown_function('InitializeShutdown');

function InitializeShutdown(){
    include_once BITsensorBasePath . 'Core/Handler/AfterRequestHandler.php';
    AfterRequestHandler::Handle();
}

/*@var $BITsensor Collector*/
$BITsensor = new Collector();

include_once BITsensorBasePath . 'Core/Handler/HttpRequestHandler.php';
HttpRequestHandler::Handle();
include_once BITsensorBasePath . 'Core/Handler/RequestInputHandler.php';
RequestInputHandler::Handle();

$BITsensor->SetInputProcessed(true);
$BITsensor->SetContextProcessed(true);