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
        
        AfterRequestHandler::fatalHandler();
        
        SqlErrorHandler::Handle();
        ReportingHandler::Handle();
        
        AfterRequestHandler::$executed = true;
    }
    
    private static function fatalHandler() 
    {
        $errfile = "unknown file";
        $errstr  = "shutdown";
        $errno   = E_CORE_ERROR;
        $errline = 0;

        $error = error_get_last();

        if( $error !== NULL) {
          $errno   = $error["type"];
          $errfile = $error["file"];
          $errline = $error["line"];
          $errstr  = $error["message"];

          CodeErrorHandler::Handle($errno, $errstr, $errfile, $errline);
        }
    }
}