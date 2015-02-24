<?php
define('BITsensorBasePath', realpath(dirname(__FILE__)) . '/');

include_once 'Config.php';
include_once BITsensorBasePath . 'Core/Log/Collector.php';
include_once BITsensorBasePath . 'Core/Handler/CodeErrorHandler.php';
include_once BITsensorBasePath . 'Core/Handler/AfterRequestHandler.php';
include_once BITsensorBasePath . 'Core/Handler/HttpRequestHandler.php';
include_once BITsensorBasePath . 'Core/Handler/RequestInputHandler.php';

/*@var $BITsensor Collector*/
global $BITsensor;
$BITsensor = new Collector();

set_error_handler("CodeErrorHandler::Handle");
register_shutdown_function('AfterRequestHandler::Handle');

HttpRequestHandler::Handle();
RequestInputHandler::Handle();

$BITsensor->SetInputProcessed(true);
$BITsensor->SetContextProcessed(true);