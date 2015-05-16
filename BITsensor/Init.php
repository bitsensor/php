<?php
namespace BITsensor;


use BITsensor\Core\Handler\HttpRequestHandler;
use BITsensor\Core\Handler\RequestInputHandler;
use BITsensor\Core\Log\Collector;

define('BITsensorBasePath', realpath(dirname(__FILE__)) . '/');

spl_autoload_register(function ($class) {
    restore_include_path();
    set_include_path(get_include_path() . PATH_SEPARATOR . dirname(BITsensorBasePath) . PATH_SEPARATOR . dirname(BITsensorBasePath) . '/External/');
    include str_replace("\\", "/", $class) . '.php';
});

require_once 'Config.php';

ob_start();

/*@var $BITsensor Collector*/
global $BITsensor;
$BITsensor = new Collector();

set_error_handler("\\BITsensor\\Core\\Handler\\CodeErrorHandler::Handle");
register_shutdown_function('\\BITsensor\\Core\\Handler\\AfterRequestHandler::Handle');

HttpRequestHandler::Handle();
RequestInputHandler::Handle();

$BITsensor->SetInputProcessed(true);
$BITsensor->SetContextProcessed(true);