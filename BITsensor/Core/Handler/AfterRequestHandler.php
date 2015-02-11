<?php
include_once 'SqlErrorHandler.php';

class AfterRequestHandler
{
    public static function Handle()
    {
        global $BITsensor;
        SqlErrorHandler::Handle();

        echo '<pre>';
        print_r($BITsensor->Get());
    }
}