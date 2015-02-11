<?php
/* @var $BITsensor Collector */
include BITsensorBasePath . 'Core/Log/IServerError.php';

function raiseServerError($errorNumber, $errorFile)
{
    global $BITsensor;
    $BITsensor->DeleteContext(array('Script', 'Path'));
    $BITsensor->AddError(new ServerError($errorNumber, $errorFile));
}