<?php
include_once 'SqlErrorHandler.php';
include_once 'ReportingHandler.php';

class AfterRequestHandler
{
    static $executed = false;
    
    public static function Handle()
    {
        global $BITsensor;
        
        if(AfterRequestHandler::$executed === true)
            return;
        
        SqlErrorHandler::Handle();
        ReportingHandler::Handle();
        
        AfterRequestHandler::$executed = true;
    }
}