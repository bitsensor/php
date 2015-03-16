<?php
include_once 'SqlErrorHandler.php';
include_once 'ReportingHandler.php';

class AfterRequestHandler
{
    static $executed = false;
    
    public static function Handle()
    {
        if(AfterRequestHandler::$executed === true)
            return;
        AfterRequestHandler::$executed = true;

        global $BITsensor;
        
        AfterRequestHandler::fatalHandler();
        
        SqlErrorHandler::Handle();
        ReportingHandler::Handle();
    }
    
    private static function fatalHandler() 
    {
        $errfile = "";
        $errstr  = "Fatal Error";
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